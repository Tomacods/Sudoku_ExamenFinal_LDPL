<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/sudoku', 'Sudoku::index');
$routes->post('/sudoku/validar', 'Sudoku::validar');
$routes->get('/registro', 'AuthController::registro');
$routes->post('/registro/guardar', 'AuthController::guardarUsuario');
$routes->get('/login', 'AuthController::login');

$routes->post('/login/autenticar', 'AuthController::autenticar');
$routes->get('/logout', 'AuthController::logout');

// Ruta del panel usando el Controlador Nuevo
$routes->get('panel', 'Panel::index');

// Ruta para crear el juego (la vamos a usar en el próximo paso)
$routes->post('sudoku/crear', 'Sudoku::crearPartida');
