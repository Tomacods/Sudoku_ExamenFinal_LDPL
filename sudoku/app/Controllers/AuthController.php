<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function registro()
    {
        return view('auth/registro');
    }

    public function login()
    {
        return view('auth/login');
    }


    public function guardarUsuario()
    {
        $userModel = new UserModel();

        // agarro los datos del formulario
        $data = [
            'nombre'   => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'email'    => $this->request->getPost('email'),
            'usuario'  => $this->request->getPost('usuario'),
            'password' => $this->request->getPost('password')
        ];

        // valido 
        if (!$userModel->validate($data)) {
            // Si falla la validación vuelvo para atras
            return redirect()->back()->withInput()->with('errors', $userModel->errors());
        }

        // si pasa el control guardo los datos
        $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);

        $userModel->insert($data);

        return redirect()->to('login')->with('mensaje', '¡Registro exitoso! Ahora iniciá sesión.');
    }


    public function autenticar()
    {
        $userModel = new UserModel();
        $usuario = $this->request->getPost('usuario');
        $password = $this->request->getPost('password');

        $datosUsuario = $userModel->where('usuario', $usuario)->first();

        if ($datosUsuario) {
            if (password_verify($password, $datosUsuario['password'])) {
                $sessionData = [
                    'id'       => $datosUsuario['id'],
                    'nombre'   => $datosUsuario['nombre'],
                    'usuario'  => $datosUsuario['usuario'],
                    'logueado' => true
                ];
                session()->set($sessionData);
                return redirect()->to('panel');
            }
        }
        return redirect()->to('login')->with('error', 'Usuario o contraseña incorrectos ❌');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('login');
    }
}
