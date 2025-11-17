<?php

namespace App\Controllers;

use App\Models\UserModel;

class Panel extends BaseController
{
    public function index()
    {
        // verifico si está logueado
        if (!session()->get('logueado')) {
            return redirect()->to('login');
        }

        $db = \Config\Database::connect();
        $usuarioId = session()->get('id');

        // Busco la última partida del usuario 
        // hago una consulta a la base de datos de la ultima partida del usuario
        $ultimaPartida = $db->table('partidas')
            ->where('usuario_id', $usuarioId)
            ->orderBy('fecha', 'DESC')
            ->get()
            ->getRowArray(); // Trae solo una fila

        //con esto mando los datos a la vista
        $datos = [
            'nombre' => session()->get('nombre'),
            'ultimaPartida' => $ultimaPartida
        ];

        return view('panel/dashboard', $datos);
    }
}
