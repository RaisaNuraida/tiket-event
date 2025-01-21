<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


/**
 * Route Login
 */
$routes->get('/login', 'LoginController::index');
$routes->post('/login', 'LoginController::login');
$routes->get('/logout', 'LoginController::logout');

/**
 * Route Register
 */
$routes->get('/register', 'RegisterController::index');   // Menampilkan form registrasi
$routes->post('/register', 'RegisterController::register'); // Menangani registrasi form

/**
 * Route Admin
 */
$routes->get('admin/index', 'AdminController::admin_index');
$routes->get('admin/events', 'AdminController::admin_events');
$routes->get('admin/users', 'AdminController::admin_users');

/**
 * Route Kasir
 */
$routes->get('kasir/index', 'KasirController::kasir_index');
$routes->get('kasir/events', 'KasirController::kasir_events');

/**
 * Route Owner
 */
$routes->get('owner/index', 'OwnerController::owner_index');