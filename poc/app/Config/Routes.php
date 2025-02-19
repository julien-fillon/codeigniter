<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->GET('/', 'HomeController::index');
$routes->GET('/login', 'AuthController::login');
$routes->POST('/auth/loginSubmit', 'AuthController::loginSubmit');
$routes->GET('/logout', 'AuthController::logout');

# Dashboard
$routes->GET('/dashboard', 'DashboardController::index', ['filter' => 'authGuard']);

# Dashboard Images
$routes->GET('/dashboard/images', 'ImageController::index', ['filter' => 'authGuard']);
$routes->POST('/dashboard/images', 'ImageController::upload', ['filter' => 'authGuard']);
$routes->POST('/dashboard/images/update/(:num)', 'ImageController::update/$1', ['filter' => 'authGuard']);
$routes->GET('/dashboard/images/delete/(:num)', 'ImageController::delete/$1', ['filter' => 'authGuard']);

# Dashboard Events
$routes->GET('/dashboard/events', 'EventController::index', ['filter' => 'authGuard']);
$routes->GET('/dashboard/events/create', 'EventController::create', ['filter' => 'authGuard']);
$routes->POST('/dashboard/events/store', 'EventController::store');
$routes->GET('/dashboard/events/edit/(:num)', 'EventController::edit/$1', ['filter' => 'authGuard']);
$routes->POST('/dashboard/events/update/(:num)', 'EventController::update/$1', ['filter' => 'authGuard']);
$routes->GET('/dashboard/events/delete/(:num)', 'EventController::delete/$1', ['filter' => 'authGuard']);
