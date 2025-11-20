<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
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

$routes->post('/sudoku/validar', 'Sudoku::validar');

$routes->get('/registro', 'AuthController::registro');

$routes->post('/registro/guardar', 'AuthController::guardarUsuario');
$routes->get('/login', 'AuthController::login');

$routes->post('/login/autenticar', 'AuthController::autenticar');


$routes->get('/logout', 'AuthController::logout');

// Ruta del panel usando el Controlador Nuevo
$routes->get('panel', 'Panel::index');

// Ruta para crear el juego 
$routes->post('sudoku/crear', 'Sudoku::crearPartida');
