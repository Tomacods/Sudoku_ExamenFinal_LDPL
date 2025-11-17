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
$routes->get('/panel', function () {
    echo "<h1>¡Bienvenido " . session('nombre') . "! 🎮</h1>";
    echo "<p>Acá vas a elegir la dificultad del Sudoku.</p>";
    echo "<a href='" . base_url('logout') . "'>Cerrar Sesión</a>";
});
