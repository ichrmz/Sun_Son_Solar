<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

// Registration (customers only, per the client)
$routes->get('register', 'Register::index');
$routes->post('register', 'Register::store');
$routes->get('register/success', 'Register::success');
