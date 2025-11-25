<?php

namespace App\Libraries;

/**
 * Clase de servicio para toda la lógica del juego Sudoku.
 * Se encarga de generar tableros, validarlos y aplicar las reglas del juego.
 */
class SudokuService
{
    /**
     * Genera un nuevo puzzle de Sudoku con un número de pistas objetivo.
     *
     * @param int $pistasObjetivo El número de celdas visibles deseadas.
     * @return array Contiene 'tableroJuego' y 'tableroResuelto'.
     */
    public function generarPuzzle(int $pistasObjetivo): array
    {
        $intentos = 0;
        $maxIntentos = 200;// Límite de seguridad para evitar un bucle infinito si la generación falla.

          // el bucle intenta generar un tablero que cumpla exactamente con el número de pistas.
        // A veces, al quitar una celda, el tablero resultante tiene más pistas de las deseadas en ese caso, se descarta y se genera uno nuevo.
        do {
            $tableroResuelto = $this->generarTableroValido(); //genera un tablero resuelto completo
            $tableroJuego = $this->ocultarCeldasInteligente($tableroResuelto, $pistasObjetivo); //oculta celdas para crear el juego
            $pistasReales = count(array_filter($tableroJuego));
            $intentos++;
        } while ($pistasReales > $pistasObjetivo && $intentos < $maxIntentos); // revisa que no se exceda el número de pistas objetivo

        return [
            'tablero_juego'    => $tableroJuego,
            'tablero_resuelto' => $tableroResuelto,
        ];
    }

    /**
     * Genera un tablero de Sudoku 4x4 completamente resuelto y válido.
     */
    private function generarTableroValido(): array
    {
        $base = [1, 2, 3, 4, 3, 4, 1, 2, 2, 1, 4, 3, 4, 3, 2, 1]; // Solución válida base
        return $this->mezclarNumeros($base);
    }

    private function mezclarNumeros(array $tablero): array
    {
        $map = [1, 2, 3, 4];
        shuffle($map);
        $nuevoTablero = [];
        foreach ($tablero as $val) {
            $nuevoTablero[] = $map[$val - 1];
        }
        return $nuevoTablero;
    }

    /**
     * Oculta celdas de un tablero resuelto asegurando que la solución siga siendo única.
     */

    /**
     * Lógica principal para crear un puzzle con una única solución.
     * Toma un tablero resuelto y elimina celdas una por una, asegurándose
     * de que el puzzle resultante siga teniendo una sola solución posible.
     */
    private function ocultarCeldasInteligente(array $tablero, int $pistasObjetivo): array
    {
        $tableroJuego = $tablero;
        $indices = range(0, 15);//crea un arrray
        shuffle($indices); //ordena aleatoriamente el array

        $celdasLlenas = 16;

        foreach ($indices as $pos) {
            if ($celdasLlenas <= $pistasObjetivo) break; //si alcanzó el número de pistas objetivo, termina

            $valorOriginal = $tableroJuego[$pos];
            $tableroJuego[$pos] = null; //el null representa una celda vacía

            $soluciones = 0; //contador de soluciones 
            $tableroCopia = $tableroJuego; //crea una copia para no modificar el original
            $this->contarSoluciones($tableroCopia, $soluciones);

            if ($soluciones != 1) { //si las soluciones no son únicas, restaura el valor, es decir, no se puede ocultar esa celda
                $tableroJuego[$pos] = $valorOriginal;
            } else { //si la solución sigue siendo única, confirma la eliminación
                $celdasLlenas--;
            }
        }
        return $tableroJuego; //devuelve el tablero con las celdas ocultas
    }

    private function contarSoluciones(array &$board, int &$count): void //funcion recursiva para contar soluciones
    {
        if ($count > 1) return; //si encuentra más de una solución, no sigue buscando

        $vacio = -1; // posición de la celda vacía
        for ($i = 0; $i < 16; $i++) { //recorre las celdas del tablero buscando una vacía
            if (empty($board[$i])) { //si la celda está vacía entonces 
                $vacio = $i; // guarda la posición de la celda vacía
                break;
            }
        }

        if ($vacio == -1) { // Si no hay celdas vacías, se encontró una solución completa
            $count++;
            return;
        }

        for ($num = 1; $num <= 4; $num++) { // intenta números del 1 al 4 para la celda vacía
            if ($this->esValido($board, $vacio, $num)) {
                $board[$vacio] = $num;
                $this->contarSoluciones($board, $count);
                $board[$vacio] = null;
            }
        }
    }
    //https://stackoverflow.com/questions/70612017/sudoku-solver-code-gives-unexpected-result <-- referencia para la función de validación 

    /**Comprueba si un número es válido en una posición específica del tablero
     * según las reglas del Sudoku (no repetido en fila, columna y bloque 2x2). */
    private function esValido(array $board, int $pos, int $num): bool 
    {
        $fila = floor($pos / 4); //fila actual
        $col = $pos % 4; //columna actual

        for ($c = 0; $c < 4; $c++) if ($board[$fila * 4 + $c] == $num) return false;
        for ($f = 0; $f < 4; $f++) if ($board[$f * 4 + $col] == $num) return false;

        // Verificar el bloque 2x2 
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