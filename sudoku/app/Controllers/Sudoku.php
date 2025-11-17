<?php

namespace App\Controllers;

class Sudoku extends BaseController
{
    public function index()
    {
        // Carga la vista del tablero
        return view('tablero');
    }

    public function validar()
    {
        // Este método lo usaremos después para el formulario
        echo "Acá vamos a validar el sudoku";
    }
}
