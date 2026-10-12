<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('login', 'Home::index');
$routes->get('/register', 'UserController::register');
$routes->post('/register', 'UserController::store');
$routes->get('/loggedin', 'UserController::loggedin');
