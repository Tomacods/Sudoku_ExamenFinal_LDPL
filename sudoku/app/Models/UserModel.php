<?php

namespace App\Models; //https://codeigniter.com/user_guide/models/model.html#models
//de acá sacaque la config para poder hacer el modelo y la validación

use CodeIgniter\Model;

/** Este modelo se encarga de la gestión de los datos de la tabla 'usuarios'.
 * Proporciona métodos para interactuar con la base de datos para operaciones CRUD
 * sobre los usuarios, además de manejar la validación de los datos antes de la inserción actualización.
 */
class UserModel extends Model
{
    protected $table            = 'usuarios';  /** * El nombre de la tabla de la base de datos que este modelo representa. */

    protected $primaryKey       = 'id'; /*** El nombre de la clave primaria de la tabla.*/
    
    
    protected $allowedFields    = ['nombre', 'apellido', 'email', 'usuario', 'password']; /*** Un array con los nombres de los campos de la tabla que se pueden
     *insertar o actualizar de forma masiva. Es una medida de seguridad para evitar que se modifiquen columnas no deseadas.*/

    protected $useTimestamps    = false;  /**Si es `true`, el modelo gestionará automáticamente las columnas
     *           `created_at` y `updated_at`. En este caso, está desactivado. */

    
    protected $validationRules = [ /** * Define las reglas de validación que se aplicarán a los datos  antes de ser guardados en la base de datos.
     *            CodeIgniter utiliza estas reglas al llamar a los métodos `insert()` o `update()`.*/
        'nombre'   => 'required|min_length[3]',
        'apellido' => 'required|min_length[3]',
        'email'    => 'required|valid_email|is_unique[usuarios.email]',
        'usuario'  => 'required|min_length[3]|is_unique[usuarios.usuario]',
        'password' => 'required|min_length[4]'
    ];

    /** Define mensajes de error personalizados para las reglas de validación.
     * Esto permite mostrar mensajes más amigables al usuario en caso de queuna regla de validación falle.*/
    protected $validationMessages = [
        'email' => [
            'is_unique' => 'Ese correo ya está registrado. Probá con otro.'
        ],
        'usuario' => [
            'is_unique' => 'El usuario ya existe.'
        ]
    ];
}
