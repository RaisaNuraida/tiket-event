<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


/**
 * Route Login
 */
// GET
$routes->get('/', 'LoginController::index');
$routes->get('/login', 'LoginController::index');
$routes->get('/logout', 'LoginController::logout');
// POST
$routes->post('/login', 'LoginController::login');

/**
 * Route Admin 
 * Index
 */
$routes->get('admin/index', 'AdminController::admin_index');

/**
 * Route Admin
 * Event
 */
$routes->get('admin/events', 'AdminController::admin_events');

/**
 * Route Admin 
 * Users
 */
// GET
$routes->get('admin/users', 'AdminController::admin_users');

// POST
$routes->post('admin/users', 'AdminController::add_users');
$routes->post('admin/users/update_user/(:num)', 'AdminController::edit_users/$1');
$routes->post('admin/users/update_status', 'AdminController::update_status');

/**
 * Route Kasir
 */
$routes->get('kasir/index', 'KasirController::kasir_index');
$routes->get('kasir/events', 'KasirController::kasir_events');

/**
 * Route Owner
 */
$routes->get('owner/index', 'OwnerController::owner_index');