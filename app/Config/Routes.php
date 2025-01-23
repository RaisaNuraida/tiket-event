<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


/**
 * Route Login
 */
$routes->get('/', 'LoginController::index');
$routes->get('/login', 'LoginController::index');
$routes->get('/logout', 'LoginController::logout');

$routes->post('/login', 'LoginController::login');

/**
 * Route Admin 
 * Index
 */
$routes->get('admin/index', 'Admin\DashboardController::admin_index');

/**
 * Route Admin
 * Event
 */
$routes->get('admin/events', 'Admin\EventController::admin_events');

/**
 * Route Admin 
 * Users
 */

// GET
$routes->get('admin/users', 'Admin\UsersController::admin_users');

// POST
$routes->post('admin/users', 'Admin\UsersController::add_users');
$routes->post('admin/users/update_user/(:num)', 'Admin\UsersController::edit_users/$1');
$routes->post('admin/users/update_status/(:num)', 'Admin\UsersController::update_status/$1');

/**
 * Route Kasir
 */
$routes->get('kasir/index', 'Kasir\DashboardController::kasir_index');
$routes->get('kasir/events', 'Kasir\EventController::kasir_events');

/**
 * Route Owner
 */
$routes->get('owner/index', 'Owner\DashboardController::owner_index');