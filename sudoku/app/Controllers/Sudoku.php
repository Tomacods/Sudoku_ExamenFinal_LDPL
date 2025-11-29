<?php

namespace App\Controllers;

use App\Libraries\SudokuService;

/** Controlador principal para toda la lógica del juego
 * Se encarga de mostrar el tablero, crear nuevas partidas, validar los resultados
 * y generar los tableros de juego.*/
class Sudoku extends BaseController
{
    private $sudokuService;

    public function __construct() {
        $this->sudokuService = new SudokuService();
    }
    public function index()
    {
        // Usando el helper `session()` de CodeIgniter, se comprueba si existe una partida en curso.
        // `has('tablero_juego')` devuelve `true` si la clave 'tablero_juego' existe en la sesión,  Si no hay partida, se redirige al panel de usuario.
        if (!session()->has('tablero_juego')) {
            return redirect()->to('panel'); //  crea una respuesta de redirección a la ruta 'panel'.
        }

        // Se recuperan datos guardados previamente en la sesión.
        $dificultadActual = session()->get('dificultad_actual'); 
        $usuarioId = session()->get('id');
        $db = \Config\Database::connect();  // es el método de CodeIgniter para obtener la instancia de la base de datos.

        // Se utiliza el Query Builder de CodeIgniter para construir una consulta SQL de forma segura.
        // obtiene los 5 mejores tiempos globales para la dificultad actual.
        $rankingGlobal = $db->table('partidas')
            ->select('partidas.*, usuarios.usuario as nombre_jugador') 
            ->join('usuarios', 'usuarios.id = partidas.usuario_id')   
            ->where('partidas.nivel', $dificultadActual)              
            ->where('partidas.resultado', 'victoria')               
            ->orderBy('partidas.tiempo_segundos', 'ASC')              
            ->limit(5)                                     
            ->get()                                                   // Ejecuta la consulta.
            ->getResultArray();                                       // Devuelve todos los resultados como un array de arrays.

        //  5 mejores tiempos del usuario actual.
        $rankingPersonal = $db->table('partidas')
            ->select('partidas.*, usuarios.usuario as nombre_jugador')
            ->join('usuarios', 'usuarios.id = partidas.usuario_id')
            ->where('partidas.usuario_id', $usuarioId) // La única diferencia: filtra por el ID del usuario logueado.
            ->where('partidas.resultado', 'victoria')
            ->orderBy('partidas.tiempo_segundos', 'ASC')
            ->limit(5)
            ->get()
            ->getResultArray();

        // Se agrupan todos los datos necesarios para la vista en un array.
        $data = [
            'tablero'         => session()->get('tablero_juego'), 
            'dificultad'      => $dificultadActual,
            'rankingGlobal'   => $rankingGlobal,
            'rankingPersonal' => $rankingPersonal,
            'hora_inicio'     => session()->get('hora_inicio') // Para el cronómetro
        ];
        return view('tablero', $data); // funcion de CodeIgniter que carga un archivo de vista.
    }

    /**
     * Crea una nueva partida. Se llama desde el panel de usuario al elegir una dificultad.
     * Genera un tablero, lo guarda en la sesión y redirige al jugador a la pantalla de juego.
     */
    public function crearPartida()
    {
        // Se obtiene la dificultad ('facil', 'medio', 'dificil') enviada desde un formulario.
        $dificultad = $this->request->getPost('dificultad');       // es el objeto de CodeIgniter que maneja las peticiones HTTP 

        // Se define cuántas pistas debe tener el tablero según la dificultad.
        $pistasObjetivo = 8; // Fácil
        if ($dificultad == 'medio') $pistasObjetivo = 6;
        if ($dificultad == 'dificil') $pistasObjetivo = 4;

        // El controlador  pide un puzzle.
        $puzzle = $this->sudokuService->generarPuzzle($pistasObjetivo);

        // Se guardan los datos de la nueva partida en la sesión del usuario.
        session()->set([// puede tomar un array para establecer múltiples valores a la vez.
            'tablero_resuelto'  => $puzzle['tablero_resuelto'], // La solución completa, para validarla después.
            'tablero_juego'     => $puzzle['tablero_juego'],    // El tablero con huecos, para mostrar al jugador.
            'dificultad_actual' => $dificultad,
            'hora_inicio'       => time()             // devuelve el timestamp actual para calcular la duración.
        ]);
        
        return redirect()->to('sudoku'); // Se redirige al usuario a la URL /sudoku, que ejecutará el método `index` de este controlador.
    }

    /*Valida la solución enviada por el usuario. Esta función es llamada mediante una petición AJAX desde el frontend.*/
    public function validar()//determina si el jugador ganó o perdio
    {
        helper('url'); 

        if (!session()->has('tablero_resuelto')) {
            return $this->response
                        ->setContentType('application/json')
                        ->setBody(json_encode(['status' => 'error', 'msg' => 'La sesión expiró.']));
        }

        $solucionReal = session()->get('tablero_resuelto');
        $usuarioId = session()->get('id');
        $dificultad = session()->get('dificultad_actual');
        $inicio = session()->get('hora_inicio');

        $esCorrecto = true;
        for ($i = 0; $i < 16; $i++) { //este for recorre el tablero 
            if ($this->request->getPost('c' . $i) != $solucionReal[$i]) {
                $esCorrecto = false;
                break;
            }
        }

        $tiempoSegundos = time() - $inicio;
        $db = \Config\Database::connect();

        if ($esCorrecto) {
            // 1. Guardar Victoria
            $db->table('partidas')->insert([
                'usuario_id'      => $usuarioId,
                'nivel'           => $dificultad,
                'tiempo_segundos' => $tiempoSegundos,
                'fecha'           => date('Y-m-d H:i:s'),
                'resultado'       => 'victoria'
            ]);

            // 2. BUSCAR RANKINGS ACTUALIZADOS (Esto es lo que faltaba)
            $rankingGlobal = $db->table('partidas')
                ->select('partidas.*, usuarios.usuario as nombre_jugador')
                ->join('usuarios', 'usuarios.id = partidas.usuario_id')
                ->where('partidas.nivel', $dificultad)
                ->where('partidas.resultado', 'victoria')
                ->orderBy('partidas.tiempo_segundos', 'ASC')
                ->limit(5)
                ->get()->getResultArray();

            $rankingPersonal = $db->table('partidas')
                ->select('partidas.*, usuarios.usuario as nombre_jugador')
                ->join('usuarios', 'usuarios.id = partidas.usuario_id')
                ->where('partidas.usuario_id', $usuarioId)
                ->where('partidas.resultado', 'victoria')
                ->orderBy('partidas.tiempo_segundos', 'ASC')
                ->limit(5)
                ->get()->getResultArray();

            session()->remove(['tablero_juego', 'tablero_resuelto']);

            // 3. Enviar todo en el JSON
            $respuesta = [
                'status' => 'success',
                'msg' => "¡GANASTE! 🏆 Tiempo: $tiempoSegundos segundos.",
                'redirect' => base_url('panel'),
                // agrego estos datos para que el json no falle
                'rankingGlobal' => $rankingGlobal,
                'rankingPersonal' => $rankingPersonal
            ];

            return $this->response
                        ->setContentType('application/json')
                        ->setBody(json_encode($respuesta));

        } else {
            // DERROTA
            $db->table('partidas')->insert([
                'usuario_id'      => $usuarioId,
                'nivel'           => $dificultad,
                'tiempo_segundos' => $tiempoSegundos,
                'fecha'           => date('Y-m-d H:i:s'),
                'resultado'       => 'derrota'
            ]);

            $respuesta = [
                'status' => 'error',
                'msg' => 'Has perdido esta partida. Había errores en el tablero.'
            ];

            return $this->response
                        ->setContentType('application/json')
                        ->setBody(json_encode($respuesta));
        }
    }
}
