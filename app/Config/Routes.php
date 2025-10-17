<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Rutas para Averías
$routes->group('averias', function($routes) {
    $routes->get('/', 'Averias::index');
    $routes->get('listar', 'Averias::listar');
    $routes->get('registrar', 'Averias::registrar');
    $routes->post('guardar', 'Averias::guardar');
    $routes->get('actualizar/(:num)', 'Averias::actualizar/$1');
});
