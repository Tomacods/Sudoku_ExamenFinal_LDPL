<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

//el archivo routes es el componente que le dice a codeigniter que hacer cuando se accede a una url especifica
//esto sirve como seguridad para que no se pueda acceder a ciertas rutas sin estar logueado, codeigniter tira un error 404 si no se cumple la condicion
//tambien sirve para tener url amigables, es decir limpias
//la funcion principal es desacoplar la url del sistema de archivos, el archivo intercepta la peticion http y determina que controlador y metodo se va a ejecutar
//

//  la raíz que  lleva directo al login
$routes->get('/', function () {
    // Si existe la sesión 'logueado', va al panel directo
    if (session()->get('logueado')) {
        return redirect()->to('panel');
    }
    // Si no, lo mando al login
    return redirect()->to('login');
});


$routes->get('/sudoku', 'Sudoku::index'); //este es el index del sudoku

$routes->post('/sudoku/validar', 'Sudoku::validar'); //esta ruta usa post porque envia datos

$routes->get('/registro', 'AuthController::registro'); //esta ruta muestra el formulario de registro

$routes->post('/registro/guardar', 'AuthController::guardarUsuario'); //esta ruta procesa el formulario de registro

$routes->get('/login', 'AuthController::login'); //esta ruta muestra el formulario de login

$routes->post('/login/autenticar', 'AuthController::autenticar'); //esta ruta procesa el formulario de login

$routes->get('/logout', 'AuthController::logout'); //esta ruta cierra la sesión del usuario

// Ruta del panel usando el Controlador Nuevo
$routes->get('panel', 'Panel::index');

// Ruta para crear el juego 
$routes->post('sudoku/crear', 'Sudoku::crearPartida');


//metodos get usados unicamente para lectura

//metodos post usado para acciones que cambian el estado del servidor, y para enviar datos sensibles 
