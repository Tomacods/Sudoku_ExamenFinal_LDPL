<?php

namespace App\Controllers;

class Sudoku extends BaseController
{
    public function index()
    {
        // Si alguien entra directo a /sudoku sin "crear partida", lo mandamos al panel
        if (!session()->has('tablero_juego')) {
            return redirect()->to('panel');
        }

        $data = [
            'tablero' => session()->get('tablero_juego'),
            'dificultad' => session()->get('dificultad_actual')
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
        $base = [
            1,
            2,
            3,
            4,
            3,
            4,
            1,
            2,
            2,
            1,
            4,
            3,
            4,
            3,
            2,
            1
        ];

        // Acá podrías agregar lógica para mezclar filas y columnas
        // para que no sea siempre el mismo, pero para el examen esta base sirve.
        // Un truco simple: intercambiar números aleatoriamente (ej: cambiar todos los 1 por 4)
        return $this->mezclarNumeros($base);
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
    public function validar()
    {
        echo "Validando...";
    }
}
