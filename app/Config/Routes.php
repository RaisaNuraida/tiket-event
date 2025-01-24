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
 * ADMIN 
 * Index
 */
// GET
$routes->get('admin/index', 'Admin\DashboardController::admin_index');
// POST



/**
 * ADMIN
 * Event
 */
// GET
$routes->get('admin/events', 'Admin\EventController::admin_events');
// POST



/**
 * ADMIN 
 * Users
 */
// GET
$routes->get('admin/users', 'Admin\UsersController::admin_users');
$routes->get('admin/users/filter_users', 'Admin\UsersController::filter_users');
// POST
$routes->post('admin/users/add_user', 'Admin\UsersController::add_users');
$routes->post('admin/users/update_user/(:num)', 'Admin\UsersController::edit_users/$1');
$routes->post('admin/users/update_status/(:num)', 'Admin\UsersController::update_status/$1');



/**
 * Route Kasir
 */
// GET
$routes->get('kasir/index', 'Kasir\DashboardController::kasir_index');
$routes->get('kasir/events', 'Kasir\EventController::kasir_events');
// POST

/**
 * Route Owner
 */
// GET
$routes->get('owner/index', 'Owner\DashboardController::owner_index');
// POST