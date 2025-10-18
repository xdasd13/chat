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
    $routes->post('guardar', 'Averias::guardar', ['filter' => 'csrf']);
    $routes->get('soluciones', 'Averias::soluciones');
    $routes->post('marcarSolucionada/(:num)', 'Averias::marcarSolucionada/$1', ['filter' => 'csrf']);
});
