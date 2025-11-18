<?php

namespace App\Controllers;

class Sudoku extends BaseController
{
    public function index()
    {
        if (!session()->has('tablero_juego')) {
            return redirect()->to('panel');
        }

        $dificultadActual = session()->get('dificultad_actual');
        $usuarioId = session()->get('id'); // Necesitamos tu ID para el ranking personal
        $db = \Config\Database::connect();

        // 1. Ranking GLOBAL (Top 5 de todos)
        $rankingGlobal = $db->table('partidas')
            ->select('partidas.*, usuarios.usuario as nombre_jugador')
            ->join('usuarios', 'usuarios.id = partidas.usuario_id')
            ->where('partidas.nivel', $dificultadActual)
            ->where('partidas.resultado', 'victoria')
            ->orderBy('partidas.tiempo_segundos', 'ASC')
            ->limit(5)
            ->get()
            ->getResultArray();

        // 2. Ranking PERSONAL (Top 5 tuyos)
        $rankingPersonal = $db->table('partidas')
            ->select('partidas.*, usuarios.usuario as nombre_jugador')
            ->join('usuarios', 'usuarios.id = partidas.usuario_id')
            ->where('partidas.usuario_id', $usuarioId) // <-- FILTRO POR VOS
            ->where('partidas.nivel', $dificultadActual)
            ->where('partidas.resultado', 'victoria')
            ->orderBy('partidas.tiempo_segundos', 'ASC')
            ->limit(5)
            ->get()
            ->getResultArray();

        $data = [
            'tablero' => session()->get('tablero_juego'),
            'dificultad' => $dificultadActual,
            'rankingGlobal' => $rankingGlobal,    // Mandamos las dos listas
            'rankingPersonal' => $rankingPersonal // Mandamos las dos listas
        ];

        return view('tablero', $data);
    }

    public function crearPartida()
    {
        $dificultad = $this->request->getPost('dificultad');

        // Defino cuántas ayudas doy, esdecir el nivel de dificulatad
        $pistas = 8; // Fácil
        if ($dificultad == 'medio') $pistas = 6;
        if ($dificultad == 'dificil') $pistas = 4;

        // Genero un tablero valido

        $tableroResuelto = $this->generarTableroValido();

        // creo el tablero que va a usar el jugador
        $tableroJuego = $this->ocultarCeldas($tableroResuelto, $pistas);

        // Guardar todo en sesión para validarlo después
        session()->set([
            'tablero_resuelto' => $tableroResuelto, // La respuesta correcta
            'tablero_juego'    => $tableroJuego,    // Lo que ve el usuario
            'dificultad_actual' => $dificultad,
            'hora_inicio'      => time()            // Para calcular cuánto tardó
        ]);

        return redirect()->to('sudoku');
    }

    // --- FUNCIONES PRIVADAS DE LÓGICA ---

    private function generarTableroValido()
    {
        // Plantilla base de un sudoku
        $tablero2D = [
            [1, 2, 3, 4],
            [3, 4, 1, 2],
            [2, 1, 4, 3],
            [4, 3, 2, 1]
        ];

        // mezclo las filas y las columnas para que no sea siempre la misma
        $tableroMezclado2D = $this->mezclarFilasYColumnas($tablero2D);

        //
        $tableroPlano = array_merge(...$tableroMezclado2D);

        // intercambio los numeros para que sea un poco más aleatorio
        return $this->mezclarNumeros($tableroPlano);
    }

    private function mezclarFilasYColumnas($tablero)
    {
        // esto mezcla las filas dentro de los bloques
        if (rand(0, 1)) {
            list($tablero[0], $tablero[1]) = [$tablero[1], $tablero[0]];
        }
        if (rand(0, 1)) {
            list($tablero[2], $tablero[3]) = [$tablero[3], $tablero[2]];
        }

        // esto hace los mismo que lo de arriva pero para las columnas
        if (rand(0, 1)) {
            for ($i = 0; $i < 4; $i++) {
                list($tablero[$i][0], $tablero[$i][1]) = [$tablero[$i][1], $tablero[$i][0]];
            }
        }
        if (rand(0, 1)) {
            for ($i = 0; $i < 4; $i++) {
                list($tablero[$i][2], $tablero[$i][3]) = [$tablero[$i][3], $tablero[$i][2]];
            }
        }

        // Mezcla bloques de filas
        if (rand(0, 1)) {
            list($tablero[0], $tablero[2]) = [$tablero[2], $tablero[0]];
            list($tablero[1], $tablero[3]) = [$tablero[3], $tablero[1]];
        }

        return $tablero;
    }

    private function mezclarNumeros($tablero)
    {
        // Mapeo aleatorio de números 1-4
        $map = [1, 2, 3, 4];
        shuffle($map); // algo como [3, 1, 4, 2]

        $nuevoTablero = [];
        foreach ($tablero as $val) {
            // Reemplazo el valor original por su mapeo
            // -1 porque el array empieza en índice 0
            $nuevoTablero[] = $map[$val - 1];
        }
        return $nuevoTablero;
    }

    private function ocultarCeldas($tablero, $cantidadPistas)
    {
        $tableroJuego = array_fill(0, 16, null); // Tablero vacío (null)
        $indices = range(0, 15); // Índices del 0 al 15
        shuffle($indices); // Mezclo posiciones

        // agarro los primeros n indices para mostrar pistas
        for ($i = 0; $i < $cantidadPistas; $i++) {
            $pos = $indices[$i];
            $tableroJuego[$pos] = $tablero[$pos];
        }

        return $tableroJuego;
    }

    // Validar

    public function validar()
    {
        if (!session()->has('tablero_resuelto')) {
            return $this->response->setJSON(['status' => 'error', 'msg' => 'La sesión expiró. Recargá la página.']);
        }

        $solucionReal = session()->get('tablero_resuelto');
        $usuarioId = session()->get('id');
        $dificultad = session()->get('dificultad_actual');
        $inicio = session()->get('hora_inicio');

        $esCorrecto = true;

        // Validamos
        for ($i = 0; $i < 16; $i++) {
            $valorIngresado = $this->request->getPost('c' . $i);
            if ($valorIngresado != $solucionReal[$i]) {
                $esCorrecto = false;
                break; // Si ya falló uno, para qué seguir
            }
        }

        if ($esCorrecto) {
            // Guardamos en la DB
            $tiempoSegundos = time() - $inicio;
            $db = \Config\Database::connect();
            $db->table('partidas')->insert([
                'usuario_id'      => $usuarioId,
                'nivel'           => $dificultad,
                'tiempo_segundos' => $tiempoSegundos,
                'fecha'           => date('Y-m-d H:i:s'),
                'resultado'       => 'victoria'
            ]);

            // Limpiamos sesión
            session()->remove(['tablero_juego', 'tablero_resuelto']);

            // Devolvemos JSON de ÉXITO
            return $this->response->setJSON([
                'status' => 'success',
                'msg' => "¡GANASTE! 🏆 Tiempo: $tiempoSegundos segundos.",
                'redirect' => base_url('panel')
            ]);
        } else {
            // Guardamos la derrota en la DB
            $tiempoSegundos = time() - $inicio;
            $db = \Config\Database::connect();
            $db->table('partidas')->insert([
                'usuario_id'      => $usuarioId,
                'nivel'           => $dificultad,
                'tiempo_segundos' => $tiempoSegundos, // Opcional, podrías poner 0 si no te interesa el tiempo en derrotas
                'fecha'           => date('Y-m-d H:i:s'),
                'resultado'       => 'derrota'
            ]);
            // Devolvemos JSON de ERROR
            return $this->response->setJSON([
                'status' => 'error',
                'msg' => 'Has perdido esta partida'
            ]);
        }
    }
}
