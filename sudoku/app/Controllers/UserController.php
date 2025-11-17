<?php

namespace App\Controllers;

use App\Models\UserModel; // Importo el modolo

class UserController extends BaseController
{
    /**
     * Muestra la vista del formulario de registro.
     */
    public function registro_show()
    {
        // Solo muestra la vista
        return view('registro');
    }

    /**
     * Recibe los datos del POST, valida y guarda el usuario.
     */
    public function registro_save()
    {
        // 1. Cargamos el helper de formularios y el servicio de validación
        helper('form');
        $validation = \Config\Services::validation();

        // 2. Definimos las reglas de validación (¡importante!)
        // Esto chequea que los datos vengan bien antes de tocar la DB
        $rules = [
            'nombre'   => 'required|min_length[3]',
            'apellido' => 'required|min_length[3]',
            'email'    => 'required|valid_email|is_unique[usuarios.email]',
            'username' => 'required|min_length[4]|is_unique[usuarios.username]',
            'password' => 'required|min_length[6]',
            'pass_confirm' => 'required|matches[password]' // Chequea que las dos pass coincidan
        ];

        // 3. Ejecutamos la validación
        if (!$this->validate($rules)) {
            // Si la validación falla, lo mandamos de vuelta al formulario
            // Y le pasamos los errores de validación
            return view('registro', [
                'validation' => $this->validator
            ]);
        }

        // 4. Si la validación está OK, creamos el usuario
        $model = new UserModel();

        $data = [
            'nombre'   => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'email'    => $this->request->getPost('email'),
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password'), // El modelo se encarga de hashearla
        ];

        $model->save($data);

        // 5. Todo OK. Lo mandamos al login (o a donde quieras) con un mensaje
        // Usamos la Sesión para mandar un mensaje "flash"
        session()->setFlashdata('success', '¡Te registraste joya! Ahora podés loguearte.');
        return redirect()->to('/login'); // Todavía no creamos esta ruta, pero ya la dejamos
    }
}
