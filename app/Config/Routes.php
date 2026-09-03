<?php

use CodeIgniter\Router\RouteCollection;

$routes->get('/', 'Huerto::index');

$routes->post('cultivos', 'Huerto::crear');
$routes->post('cultivos/(:num)/riego', 'Huerto::registrarRiego/$1');
$routes->post('cultivos/(:num)/estado', 'Huerto::cambiarEstado/$1');
$routes->post('cultivos/(:num)/eliminar', 'Huerto::eliminar/$1');
