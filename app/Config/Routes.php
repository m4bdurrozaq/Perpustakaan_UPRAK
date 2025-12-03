<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// CORS Preflight Handler untuk semua OPTIONS request
$routes->options('(:any)', function() {
    $response = service('response');
    return $response->setStatusCode(200);
});

// Default route
$routes->get('/', 'Home::index');

// Auth routes
$routes->post('auth/login', 'Auth::login');
$routes->post('auth/logout', 'Auth::logout');
$routes->get('auth/logout', 'Auth::logout'); // Support GET juga untuk fallback
$routes->get('auth/check', 'Auth::checkAuth');
$routes->get('debug/session', function() {
return session()->get();
});

// Register
$routes->get('auth/register', 'Auth::registerForm');   // tampilkan halaman form
$routes->post('auth/register', 'Auth::register');      // proses data register

// Books routes - RESTful
$routes->get('books', 'Books::index');
$routes->get('books/(:num)', 'Books::show/$1');
$routes->post('books', 'Books::create');
$routes->put('books/(:num)', 'Books::update/$1');
$routes->delete('books/(:num)', 'Books::delete/$1');

// Atau pakai resource routes (lebih simple)
// $routes->resource('books');

// Testing routes
$routes->get('test', function() {
    return "Test route berhasil!";
});
$routes->get('api/test/users', 'Test::users');

$routes->get('test/json', function() {
    $response = service('response');
    return $response->setJSON([
        'status' => 'success',
        'message' => 'Test API berhasil!'
    ]);
});