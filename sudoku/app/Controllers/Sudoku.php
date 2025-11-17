<?php

namespace App\Controllers;

class Sudoku extends BaseController
{
    public function index()
    {
        if (!session()->has('tablero_juego')) {
            return redirect()->to('panel');
        }

        // Busco las 5 mejores partidas del usuario (Menor tiempo = Mejor)
        $db = \Config\Database::connect();
        $usuarioId = session()->get('id');

        $mejoresPartidas = $db->table('partidas')
            ->where('usuario_id', $usuarioId)
            ->where('resultado', 'victoria') // Solo las ganadas
            ->orderBy('tiempo_segundos', 'ASC') // El más rápido primero
            ->limit(5) // Solo las top 5
            ->get()
            ->getResultArray();

        $data = [
            'tablero' => session()->get('tablero_juego'),
            'dificultad' => session()->get('dificultad_actual'),
            'mejoresPartidas' => $mejoresPartidas // mando esto a la vista
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
        // esto sirve para recuperar la solucion correcta de la sesion
        if (!session()->has('tablero_resuelto')) {
            return redirect()->to('panel');
        }

        $solucionReal = session()->get('tablero_resuelto');
        $usuarioId = session()->get('id');
        $dificultad = session()->get('dificultad_actual');
        $inicio = session()->get('hora_inicio');

        // armo el tablero que mando el usuario
        $tableroUsuario = [];
        $esCorrecto = true;

        for ($i = 0; $i < 16; $i++) {
            $valorIngresado = $this->request->getPost('c' . $i);

            // Guardo lo que puso para recargar si se equivoca
            $tableroUsuario[$i] = $valorIngresado;

            // comparo con la solucion
            if ($valorIngresado != $solucionReal[$i]) {
                $esCorrecto = false;
            }
        }

        // calculo el tiempo y el resultado
        $tiempoSegundos = time() - $inicio;
        $resultado = $esCorrecto ? 'victoria' : 'derrota';

        // guardo en la base de datos
        $db = \Config\Database::connect();
        $db->table('partidas')->insert([
            'usuario_id'      => $usuarioId,
            'nivel'           => $dificultad,
            'tiempo_segundos' => $tiempoSegundos,
            'fecha'           => date('Y-m-d H:i:s'),
            'resultado'       => $resultado
        ]);

        // redirijo con mensaje
        if ($esCorrecto) {
            // Borro la partida de sesion para que no pueda volver atras
            session()->remove(['tablero_juego', 'tablero_resuelto']);

            return redirect()->to('panel')->with(
                'mensaje_juego',
                "¡GANASTE! 🏆 resolviste el nivel $dificultad en $tiempoSegundos segundos."
            );
        } else {
            return redirect()->back()
                ->with('error', '¡Ups! Hay errores en el tablero. Intentalo de nuevo.')
                ->withInput(); // esto mantiene los numeros que puso
        }
    }
}
