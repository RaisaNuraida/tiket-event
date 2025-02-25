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
$routes->get('admin/index', 'Admin\DashboardController::index');
// POST



/**
 * ADMIN
 * Event
 */
// GET
$routes->get('admin/events', 'Admin\EventController::index');
$routes->get('admin/events/filterEvent', 'Admin\EventController::filter_events');

// POST
$routes->post('admin/event/add', 'Admin\EventController::add_events');
$routes->post('admin/event/edit_events/(:num)', 'Admin\EventController::edit_events/$1');
$routes->post('admin/event/update_status/(:num)', 'Admin\EventController::update_status/$1');
$routes->match(['get', 'post'], 'admin/event/archive/(:num)', 'Admin\EventController::archive/$1');
$routes->post('admin/events/add_category', 'Admin\EventController::add_category');
$routes->post('admin/events/delete_category/(:num)', 'Admin\EventController::delete_category/$1');
$routes->post('admin/events/add_class', 'Admin\EventController::add_class');
$routes->post('admin/events/delete_class/(:num)', 'Admin\EventController::delete_class/$1');


/**
 * ADMIN 
 * Users
 */
// GET
$routes->get('admin/users', 'Admin\UsersController::index');
$routes->get('admin/users/filter_users', 'Admin\UsersController::filter_users');
// POST
$routes->post('admin/users/add_user', 'Admin\UsersController::add_users');
$routes->post('admin/users/update_user/(:num)', 'Admin\UsersController::edit_users/$1');
$routes->post('admin/users/update_status/(:num)', 'Admin\UsersController::update_status/$1');


/**
 * ADMIN
 * Archived
 */
// GET
$routes->get('admin/archive', 'Admin\ArchiveController::index');
$routes->get('admin/archive/filterEvent', 'Admin\ArchiveController::filter_events');
// POST
$routes->post('admin/archive/delete/(:num)', 'Admin\ArchiveController::delete/$1');

/**
 * ADMIN
 * Archived
 */
$routes->get('admin/activity', 'Admin\ActivityController::index');


/**
 * KASIR
 */
// GET
$routes->get('kasir/index', 'Kasir\DashboardController::kasir_index');
$routes->get('kasir/detail/(:num)', 'Kasir\DetailController::index/$1');
$routes->get('kasir/orders', 'Kasir\OrderController::orders');
$routes->get('kasir/checkout', 'Kasir\OrderController::checkout');
$routes->get('kasir/print_receipt', 'Kasir\OrderController::printReceipt');
$routes->get('kasir/print_ticket/(:segment)', 'Kasir\OrderController::printTicket/$1');
// POST
$routes->post('kasir/order_ticket', 'Kasir\OrderController::orderTicket');
$routes->post('kasir/submit_order', 'Kasir\OrderController::submitOrder');

/**
 * Route Owner
 * Dashboard
 */
// GET
$routes->get('owner/index', 'Owner\DashboardController::index');
$routes->get('owner/events', 'Owner\EventController::index');
$routes->get('owner/events/filterEvent', 'Owner\EventController::filter_events');
$routes->get('owner/users', 'Owner\UsersController::index');
$routes->get('owner/users/filter_users', 'Owner\UsersController::filter_users');
$routes->get('owner/activity', 'Owner\ActivityController::index');
$routes->get('owner/transaksi', 'Owner\TransaksiController::index');
$routes->get('owner/transaksi/downloadExcel', 'Owner\TransaksiController::downloadExcel');
// POST