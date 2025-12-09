<?php

namespace App\Controllers;

use App\Models\UserModel;
//esta clase es la que se encarga de la autenticacion de los usuarios. Su respoonsab

class AuthController extends BaseController
{
    /** Muestra la vista del formulario de registro de usuarios.*/
    public function registro() // la funcion registro sirve para mostrar el formulario de registro
    {
        // Si está logueado, lo mando al panel revisando si tiene un id 
        if (session()->has('id')) {
            return redirect()->to('panel');
        }

        return view('auth/registro');
    }

    /*Muestra la vista del formulario de inicio de sesión.*/
    public function login()
    {
        // Si está logueado, lo mando al panel
        if (session()->has('id')) {
            return redirect()->to('panel');
        }

        return view('auth/login');
    }
    /**Procesa los datos del formulario de registro.
     * Valida la información, hashea la contraseña y guarda el nuevo usuario en la base de datos.
     * Redirige al login si el registro es exitoso, o de vuelta al formulario con errores si falla.*/
    public function guardarUsuario()
    {
        $userModel = new UserModel();

        // agarro los datos del formulario
        $data = [
            'nombre'   => $this->request->getPost('nombre'), //codeIgniter uesa el getPost para obtener los datos del formulario evitando asi inyecciones sql
            'apellido' => $this->request->getPost('apellido'),
            'email'    => $this->request->getPost('email'),
            'usuario'  => $this->request->getPost('usuario'),
            'password' => $this->request->getPost('password')
        ];

        // valido los datos 
        if (!$userModel->validate($data)) {
            // Si falla la validación vuelvo para atras
            return redirect()->back()->withInput()->with('errors', $userModel->errors()); 
            //codeigniter usa el back con el withinput para mantener los datos en el formulario, seguido del with errors para mandar los errores a la vista
        } //para la presentacion: mostrar los errores en la vista y como cambia los mensajes predeterminamos con los personalizados del modelo

        // si pasa el control guardo los datos
        $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);

        $userModel->insert($data); // inserto los datos en la base de datos

        return redirect()->to('login')->with('mensaje', '¡Registro exitoso! Ahora iniciá sesión.');
    }


    /** Procesa los datos del formulario de login para autenticar al usuario.
     * Verifica el usuario y la contraseña. Si son correctos, crea una sesión
     * y redirige al panel principal. Si no, redirige al login con un mensaje de error.*/
    public function autenticar()
    {
        $userModel = new UserModel();
        $usuario = $this->request->getPost('usuario');
        $password = $this->request->getPost('password');

        $datosUsuario = $userModel->where('usuario', $usuario)->first(); //busco el usuario en la base de datos

        if ($datosUsuario) { //si encuentra el usuario
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
        return redirect()->to('login')->with('error', 'Usuario o contraseña incorrectos');
    }

    /** Cierra la sesión del usuario.
     * Destruye todos los datos de la sesión actual y redirige al formulario de login   */
    public function logout()
    {
        session()->destroy(); //el destroy es una funcion de codeigniter que destruye toda la sesion
        return redirect()->to('login');
    }
}
