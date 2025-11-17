<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'usuarios';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['nombre', 'apellido', 'email', 'usuario', 'password'];
    protected $useTimestamps    = false;

    // --- ESTAS REGLAS SON EL FILTRO DE SEGURIDAD ---
    protected $validationRules = [
        'nombre'   => 'required|min_length(3)',
        'apellido' => 'required|min_length(3)',
        'email'    => 'required|valid_email|is_unique[usuarios.email]', // <-- OJO ACÁ
        'usuario'  => 'required|min_length(3)|is_unique[usuarios.usuario]', // <-- Y ACÁ
        'password' => 'required|min_length(4)'
    ];

    protected $validationMessages = [
        'email' => [
            'is_unique' => 'Ese email ya está usado. Probá recuperar contraseña.'
        ],
        'usuario' => [
            'is_unique' => 'El usuario ya existe. Elegite otro más original.'
        ]
    ];
}
