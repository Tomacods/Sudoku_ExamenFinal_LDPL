<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'usuarios'; // El nombre de la tabla
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nombre',
        'apellido',
        'email',
        'username',
        'password'
    ]; // Estos son los campos que voy a usar

    // con esto hasheo la tabla
    protected $beforeInsert = ['hashPassword'];

    /** "Hook" que se llama justo antes de insertar un nuevo usuario.   Toma la contraseña del array $data y la reemplaza por su hash. */
    protected function hashPassword(array $data)
    {
        // Verifica si la clave 'password' existe en los datos
        if (!isset($data['data']['password'])) {
            return $data;
        }

        // Hashea la contraseña
        $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);

        return $data;
    }
}
