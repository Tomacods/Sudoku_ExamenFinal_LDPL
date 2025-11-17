<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/registro', 'UserController::registro_show');
$routes->post('/registro', 'UserController::registro_save');
