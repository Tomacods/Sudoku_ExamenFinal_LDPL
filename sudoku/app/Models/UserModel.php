<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'usuarios';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['nombre', 'apellido', 'email', 'usuario', 'password'];
    protected $useTimestamps    = false;

    // --- REGLAS DE VALIDACIÓN ---
    protected $validationRules = [
        'nombre'   => 'required|min_length(3)',
        'apellido' => 'required|min_length(3)',
        'email'    => 'required|valid_email|is_unique[usuarios.email]', // Frena mails repetidos
        'usuario'  => 'required|min_length(3)|is_unique[usuarios.usuario]', // Frena usuarios repetidos
        'password' => 'required|min_length(4)'
    ];

    protected $validationMessages = [
        'email' => [
            'is_unique' => 'Ese correo ya está registrado. Probá con otro.'
        ],
        'usuario' => [
            'is_unique' => 'El usuario ya existe. ¡Sé más original!'
        ]
    ];
}
