<?php

namespace App\Controllers;

use App\Models\UserModel;

class Panel extends BaseController
{
    //VERIFICACIÓN DE SESIÓN
    public function index()
    {
        // Se utiliza la función helper `session()` de CodeIgniter para acceder a la instancia de la sesión.
        // `->get('logueado')` intenta obtener el valor de la clave 'logueado' que se establece durante el login.
        // Si el usuario no está logueado (el valor no existe o es false), se le redirige.
        if (!session()->get('logueado')) {
            // `redirect()->to('login')` es un helper de CodeIgniter que crea una respuesta de redirección.
            return redirect()->to('login');// Envía al usuario a la ruta 'login' definida en `app/Config/Routes.php`
        }

        // CONEXIÓN A LA BASE DE DATOS:
        // de conexión a la base de datos por defecto, configurada en `app/Config/Database.php`
        $db = \Config\Database::connect();   // es la forma estándar en CodeIgniter 4 de obtener la instancia

        $usuarioId = session()->get('id'); // obtiene el ID del usuario actual, que fue guardado en la sesión durante el login

        // CONSULTA A LA BASE DE DATOS CON QUERY BUILDER:
        // Se utiliza el Query Builder de CodeIgniter para construir una consulta SQL de forma segura y legible
        // busca la partida más reciente del usuario que inició sesión
        $ultimaPartida = $db->table('partidas')
            ->where('usuario_id', $usuarioId) //  filtra por el ID del usuario
            ->orderBy('fecha', 'DESC')       // ordena los resultados descendente para la fecha más reciente
            ->get()                          //  ejecuta la consulta SELECT y devuelve un objeto de resultado
            ->getRowArray();                 // obtiene únicamente la primera fila del resultado y la devuelve como un array o si no hay devuevle null
                                
        $datos = [ // crea un array `$datos` que tiene  toda la información que la vista necesita para renderizarse.
            'nombre'        => session()->get('nombre'), // Se obtiene el nombre del usuario de la sesión.
            'ultimaPartida' => $ultimaPartida           // Se pasa el resultado de la consulta (la última partida o null).
        ];

        return view('panel/dashboard', $datos);    // view() carga y muestra un archivo de vista.
        // 'panel/dashboard' corresponde al archivo `app/Views/panel/dashboard.php`.
        // `$datos`, pasa el array a la vista, donde sus claves (`nombre`, `ultimaPartida`)
        // se convierten en variables (`$nombre`, `$ultimaPartida`) que se pueden usar en el archivo PHP de la vista.
    }
}
