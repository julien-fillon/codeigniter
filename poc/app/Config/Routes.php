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
$routes->GET('/dashboard/images/delete/(:num)', 'ImageController::delete/$1', ['filter' => 'authGuard']);
