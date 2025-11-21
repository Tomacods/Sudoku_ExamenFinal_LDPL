<?php

namespace App\Controllers;

/**
 * Controlador principal para toda la lógica del juego Sudoku.
 * Se encarga de mostrar el tablero, crear nuevas partidas, validar los resultados
 * y generar los tableros de juego.
 */
class Sudoku extends BaseController
{

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

        $intentos = 0;
        $maxIntentos = 200; // Límite de seguridad para evitar un bucle infinito si la generación falla.

        // el bucle intenta generar un tablero que cumpla exactamente con el número de pistas.
        // A veces, al quitar una celda, el tablero resultante tiene más pistas de las deseadas en ese caso, se descarta y se genera uno nuevo.
        do {
            $tableroResuelto = $this->generarTableroValido();//  genera un tablero de Sudoku 4x4 completamente resuelto.

            $tableroJuego = $this->ocultarCeldasInteligente($tableroResuelto, $pistasObjetivo);  //  Se ocultan celdas de forma inteligente para crear el puzzle.
            //  Se cuentan las pistas que quedaron realmente en el tablero.
            $pistasReales = count(array_filter($tableroJuego));  // sin un segundo argumento elimina todos los valores `null` o `false`.

            $intentos++;  // El bucle se repite si el generador no pudo reducir las pistas al objetivo exacto.
        } while ($pistasReales > $pistasObjetivo && $intentos < $maxIntentos);

        // Se guardan los datos de la nueva partida en la sesión del usuario.
        session()->set([// puede tomar un array para establecer múltiples valores a la vez.
            'tablero_resuelto'  => $tableroResuelto, // La solución completa, para validarla después.
            'tablero_juego'     => $tableroJuego,    // El tablero con huecos, para mostrar al jugador.
            'dificultad_actual' => $dificultad,
            'hora_inicio'       => time()             // devuelve el timestamp actual para calcular la duración.
        ]);

        
        return redirect()->to('sudoku'); // Se redirige al usuario a la URL /sudoku, que ejecutará el método `index` de este controlador.
    }

    /*Valida la solución enviada por el usuario. Esta función es llamada mediante una petición AJAX desde el frontend.*/
    public function validar()
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
        for ($i = 0; $i < 16; $i++) {
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
                // Agregamos los datos para que JS no falle
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


    /**Genera un tablero de Sudoku 4x4 completamente resuelto y válido */
    private function generarTableroValido()
    {
        $base = [1, 2, 3, 4, 3, 4, 1, 2, 2, 1, 4, 3, 4, 3, 2, 1]; //solucion valida
        return $this->mezclarNumeros($base);
    }

    private function mezclarNumeros($tablero)
    {
        $map = [1, 2, 3, 4];
        shuffle($map); // Se mezcla el array
        $nuevoTablero = [];
        foreach ($tablero as $val) {
            $nuevoTablero[] = $map[$val - 1];
        }
        return $nuevoTablero;
    }

    /**
     * Lógica principal para crear un puzzle con una única solución.
     * Toma un tablero resuelto y elimina celdas una por una, asegurándose
     * de que el puzzle resultante siga teniendo una sola solución posible.
     * @param array $tablero El tablero resuelto.
     * @param int $pistasObjetivo El número de celdas que deben quedar visibles.
     * @return array El tablero de juego con celdas en `null`.
     */
    private function ocultarCeldasInteligente($tablero, $pistasObjetivo)
    {
        $tableroJuego = $tablero;
        $indices = range(0, 15); // Crea un array
        shuffle($indices); // Orden aleatorio de borrado

        $celdasLlenas = 16;

        foreach ($indices as $pos) {
            // Si ya se llega al objetivo de la cantidad de pistas, se sale del bucle.
            if ($celdasLlenas <= $pistasObjetivo) break;

            $valorOriginal = $tableroJuego[$pos];

            // intenta borrar
            $tableroJuego[$pos] = null; // `null` representa una celda vacía.

        
            $soluciones = 0; // contador de soluciones
            $tableroCopia = $tableroJuego; // se crea una copia del tablero actual
            $this->contarSoluciones($tableroCopia, $soluciones);

            if ($soluciones != 1) {
                // El borrado hizo el puzzle ambiguo (múltiples soluciones) o no tiene soluciones.
                // Se restaura el número original en esa posición.
                $tableroJuego[$pos] = $valorOriginal;
            } else {
                //  Si sigue  siendo único se borra.
                $celdasLlenas--; // Decrementa el contador de celdas llenas.
            }
        }
        return $tableroJuego;
    }

    private function contarSoluciones(&$board, &$count) //funcion recursiva para contar soluciones
    {
        // si se encuentra más de una solución, no necesitamos seguir buscando.
        if ($count > 1) return;
        $vacio = -1; // Buscar la primera celda vacía.
        for ($i = 0; $i < 16; $i++) {
            if (empty($board[$i])) {
                $vacio = $i;
                break;
            }
        }
        // Caso base de la recursión: si no hay celdas vacías, encontró una solución
        if ($vacio == -1) {
            $count++;
            return;
        }
        //prueba  números del 1 al 4 en la celda vacía.
        for ($num = 1; $num <= 4; $num++) {
            // Si el número es válido para esa posición según las reglas del Sudoku
            if ($this->esValido($board, $vacio, $num)) {
                $board[$vacio] = $num; // coloca el número.
                $this->contarSoluciones($board, $count); // y llama a la función para la siguiente celda vacía.
                $board[$vacio] = null; // deshace el movimiento para probar otras posibilidades.
            }
        }
    }

    /**
     * Comprueba si un número es válido en una posición específica del tablero
     * según las reglas del Sudoku (no repetido en fila, columna y bloque 2x2). */
    private function esValido($board, $pos, $num)
    {
        // Se calculan las coordenadas de fila y columna a partir de la posición lineal
        $fila = floor($pos / 4); 
        $col = $pos % 4; //el modulo dice la columna

        // Fila
        for ($c = 0; $c < 4; $c++) if ($board[$fila * 4 + $c] == $num) return false; //hace un return false si encuentra el numero en la fila 
        // Columna
        for ($f = 0; $f < 4; $f++) if ($board[$f * 4 + $col] == $num) return false;

        // Bloque 2x2
        $startRow = floor($fila / 2) * 2;
        $startCol = floor($col / 2) * 2;
        for ($i = 0; $i < 2; $i++) {
            for ($j = 0; $j < 2; $j++) {
                if ($board[($startRow + $i) * 4 + ($startCol + $j)] == $num) return false;
            }
        }
        return true;
    }
}
