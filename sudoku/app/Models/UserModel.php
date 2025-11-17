<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'usuarios';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['nombre', 'apellido', 'email', 'usuario', 'password'];

    // Esto le avisa a CI4 que la fecha la maneja la base de datos sola
    protected $useTimestamps = false;
}
