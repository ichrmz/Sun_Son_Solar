<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Home = the front-end site (public/index.html)
$routes->get('/', static fn () => redirect()->to(base_url('index.html')));

$routes->group('api', static function ($routes) {
    $routes->post('register', 'Auth::register');
    $routes->post('login', 'Auth::login');
    $routes->post('logout', 'Auth::logout');
    $routes->get('me', 'Auth::me');
    $routes->post('profile', 'Auth::profile');
    $routes->post('password', 'Auth::password');
    $routes->post('admin/accounts', 'Auth::adminAccount');
    $routes->post('checkins', 'Auth::checkins');
});
