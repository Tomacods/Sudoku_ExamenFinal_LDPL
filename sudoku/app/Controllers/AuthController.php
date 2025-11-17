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

        // 1. Tomamos los datos del formulario
        $data = [
            'nombre'   => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'email'    => $this->request->getPost('email'),
            'usuario'  => $this->request->getPost('usuario'),
            'password' => $this->request->getPost('password')
        ];

        // 2. ¡EL FRENO DE MANO! Validamos antes de hacer nada
        if (!$userModel->validate($data)) {
            // Si falla (ej: usuario repetido), volvemos atrás con los errores
            return redirect()->back()
                ->withInput() // Para que no tenga que escribir todo de nuevo
                ->with('errors', $userModel->errors()); // Muestra la lista roja
        }

        // 3. Si pasó el control, encriptamos y guardamos
        $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);

        $userModel->insert($data);

        return redirect()->to('login')->with('mensaje', '¡Registro exitoso! Ahora iniciá sesión.');
    }
    // ... (El resto de tus funciones autenticar y logout dejadas igual)

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
