<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    // Muestra el formulario de registro
    public function registro()
    {
        return view('auth/registro');
    }

    // Procesa los datos del formulario
    public function guardarUsuario()
    {
        $userModel = new UserModel();

        // 1. Recibimos los datos CRUDOS del formulario
        $data = [
            'nombre'   => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'email'    => $this->request->getPost('email'),
            'usuario'  => $this->request->getPost('usuario'),
            'password' => $this->request->getPost('password')
        ];

        // 2. PREGUNTAMOS: ¿Son válidos estos datos?
        if (!$userModel->validate($data)) {
            // Si NO son válidos (ej: usuario repetido), volvemos atrás con los errores
            return redirect()->back()
                ->withInput() // Mantiene lo que escribió
                ->with('errors', $userModel->errors()); // Muestra la lista roja
        }

        // 3. Si pasó el filtro, recién ahí encriptamos y guardamos
        $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);

        $userModel->insert($data);

        return redirect()->to('login')->with('mensaje', '¡Registro exitoso! Ahora iniciá sesión.');
    }
    // Muestra el login (lo dejamos listo para el próximo paso)
    public function login()
    {
        return view('auth/login');
    }

    // ... (tus funciones registro y guardarUsuario ya están arriba) ...

    // Procesar el Login
    public function autenticar()
    {
        $userModel = new UserModel();

        $usuario = $this->request->getPost('usuario');
        $password = $this->request->getPost('password');

        // 1. Buscamos al usuario en la DB
        $datosUsuario = $userModel->where('usuario', $usuario)->first();

        if ($datosUsuario) {
            // 2. Verificamos la contraseña encriptada
            if (password_verify($password, $datosUsuario['password'])) {

                // 3. Crear la sesión (Login exitoso)
                $sessionData = [
                    'id'       => $datosUsuario['id'],
                    'nombre'   => $datosUsuario['nombre'],
                    'usuario'  => $datosUsuario['usuario'],
                    'logueado' => true
                ];

                session()->set($sessionData);

                // Redirigir al dashboard (donde elegirá dificultad)
                return redirect()->to('panel');
            }
        }

        // Si falló algo, volver al login con error
        return redirect()->to('login')->with('error', 'Usuario o contraseña incorrectos ❌');
    }

    // Para cerrar sesión
    public function logout()
    {
        session()->destroy();
        return redirect()->to('login');
    }
}
