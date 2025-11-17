<?php

namespace App\Controllers;

use App\Models\UserModel;

class Panel extends BaseController
{
    public function index()
    {
        // 1. Verificar si está logueado (Seguridad básica)
        if (!session()->get('logueado')) {
            return redirect()->to('login');
        }

        $db = \Config\Database::connect();
        $usuarioId = session()->get('id');

        // 2. Buscar la última partida del usuario (Requisito del examen)
        // Hacemos una consulta directa a la tabla 'partidas'
        $ultimaPartida = $db->table('partidas')
            ->where('usuario_id', $usuarioId)
            ->orderBy('fecha', 'DESC')
            ->get()
            ->getRowArray(); // Trae solo una fila

        // 3. Mandar los datos a la vista
        $datos = [
            'nombre' => session()->get('nombre'),
            'ultimaPartida' => $ultimaPartida
        ];

        return view('panel/dashboard', $datos);
    }
}
