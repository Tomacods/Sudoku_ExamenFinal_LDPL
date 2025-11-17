<?php

namespace App\Controllers;

class Sudoku extends BaseController
{
    public function index()
    {
        if (!session()->has('tablero_juego')) {
            return redirect()->to('panel');
        }

        // Buscamos las 5 mejores partidas del usuario (Menor tiempo = Mejor)
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
            'mejoresPartidas' => $mejoresPartidas // <-- Mandamos esto a la vista
        ];

        return view('tablero', $data);
    }

    public function crearPartida()
    {
        $dificultad = $this->request->getPost('dificultad');

        // 1. Definir cuántas ayudas damos según el PDF
        $pistas = 8; // Fácil
        if ($dificultad == 'medio') $pistas = 6;
        if ($dificultad == 'dificil') $pistas = 4;

        // 2. Generar un tablero RESUELTO válido (4x4)
        // (Usamos una plantilla base válida y mezclamos filas/columnas para variar)
        $tableroResuelto = $this->generarTableroValido();

        // 3. Crear el tablero de JUEGO (con huecos)
        $tableroJuego = $this->ocultarCeldas($tableroResuelto, $pistas);

        // 4. Guardar todo en sesión para validarlo después
        session()->set([
            'tablero_resuelto' => $tableroResuelto, // La respuesta correcta
            'tablero_juego'    => $tableroJuego,    // Lo que ve el usuario
            'dificultad_actual' => $dificultad,
            'hora_inicio'      => time()            // Para calcular cuánto tardó
        ]);

        return redirect()->to('sudoku');
    }

    // --- FUNCIONES PRIVADAS DE LÓGICA (El motor) ---

    private function generarTableroValido()
    {
        // Plantilla base de un Sudoku 4x4 válido
        $tablero2D = [
            [1, 2, 3, 4],
            [3, 4, 1, 2],
            [2, 1, 4, 3],
            [4, 3, 2, 1]
        ];

        // 1. Mezclamos filas y columnas para variar la estructura del tablero.
        $tableroMezclado2D = $this->mezclarFilasYColumnas($tablero2D);

        // 2. Aplanamos el tablero 2D a 1D para que sea compatible con el resto del código.
        $tableroPlano = array_merge(...$tableroMezclado2D);

        // 3. Intercambiamos los números (ej: todos los 1 por 4) para más variedad.
        return $this->mezclarNumeros($tableroPlano);
    }

    private function mezclarFilasYColumnas($tablero)
    {
        // Mezclar filas dentro de los bloques (0-1 y 2-3)
        if (rand(0, 1)) {
            list($tablero[0], $tablero[1]) = [$tablero[1], $tablero[0]];
        }
        if (rand(0, 1)) {
            list($tablero[2], $tablero[3]) = [$tablero[3], $tablero[2]];
        }

        // Mezclar columnas dentro de los bloques (0-1 y 2-3)
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

        // Mezclar bloques de filas (bloque 0-1 con bloque 2-3)
        if (rand(0, 1)) {
            list($tablero[0], $tablero[2]) = [$tablero[2], $tablero[0]];
            list($tablero[1], $tablero[3]) = [$tablero[3], $tablero[1]];
        }

        // Nota: Mezclar bloques de columnas es más complejo y con las mezclas anteriores
        // ya se consigue una gran variedad.

        return $tablero;
    }

    private function mezclarNumeros($tablero)
    {
        // Mapeo aleatorio de números 1-4
        $map = [1, 2, 3, 4];
        shuffle($map); // Queda algo como [3, 1, 4, 2]

        $nuevoTablero = [];
        foreach ($tablero as $val) {
            // Reemplazamos el valor original por su mapeo
            // -1 porque el array empieza en índice 0
            $nuevoTablero[] = $map[$val - 1];
        }
        return $nuevoTablero;
    }

    private function ocultarCeldas($tablero, $cantidadPistas)
    {
        $tableroJuego = array_fill(0, 16, null); // Tablero vacío (nulls)
        $indices = range(0, 15); // Índices del 0 al 15
        shuffle($indices); // Mezclamos posiciones

        // Tomamos los primeros N índices para mostrar pistas
        for ($i = 0; $i < $cantidadPistas; $i++) {
            $pos = $indices[$i];
            $tableroJuego[$pos] = $tablero[$pos];
        }

        return $tableroJuego;
    }

    // Validar (lo dejamos listo para el final)
    // ... (tus otras funciones index y crearPartida siguen igual) ...

    public function validar()
    {
        // 1. Recuperamos la solución correcta de la sesión
        if (!session()->has('tablero_resuelto')) {
            return redirect()->to('panel');
        }

        $solucionReal = session()->get('tablero_resuelto');
        $usuarioId = session()->get('id');
        $dificultad = session()->get('dificultad_actual');
        $inicio = session()->get('hora_inicio');

        // 2. Armamos el tablero que mandó el usuario (c0 a c15)
        $tableroUsuario = [];
        $esCorrecto = true;

        for ($i = 0; $i < 16; $i++) {
            $valorIngresado = $this->request->getPost('c' . $i);

            // Guardamos lo que puso para recargar si se equivoca (opcional)
            $tableroUsuario[$i] = $valorIngresado;

            // Comparamos con la realidad
            if ($valorIngresado != $solucionReal[$i]) {
                $esCorrecto = false;
            }
        }

        // 3. Calculamos tiempo y resultado
        $tiempoSegundos = time() - $inicio;
        $resultado = $esCorrecto ? 'victoria' : 'derrota';

        // 4. Guardamos en la Base de Datos (Requisito Examen)
        $db = \Config\Database::connect();
        $db->table('partidas')->insert([
            'usuario_id'      => $usuarioId,
            'nivel'           => $dificultad,
            'tiempo_segundos' => $tiempoSegundos,
            'fecha'           => date('Y-m-d H:i:s'),
            'resultado'       => $resultado
        ]);

        // 5. Redirigimos con mensaje
        if ($esCorrecto) {
            // Borramos la partida de sesión para que no pueda volver atrás
            session()->remove(['tablero_juego', 'tablero_resuelto']);

            return redirect()->to('panel')->with(
                'mensaje_juego',
                "¡GANASTE! 🏆 resolviste el nivel $dificultad en $tiempoSegundos segundos."
            );
        } else {
            return redirect()->back()
                ->with('error', '¡Ups! Hay errores en el tablero. Intentalo de nuevo.')
                ->withInput(); // Mantiene los números que escribió
        }
    }
}
