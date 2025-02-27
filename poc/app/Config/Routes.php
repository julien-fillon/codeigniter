<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->GET('/', 'HomeController::index');
$routes->GET('/login', 'AuthController::login', ['as' => 'auth.login']);
$routes->POST('/auth/loginSubmit', 'AuthController::loginSubmit', ['as' => 'auth.loginSubmit']);
$routes->GET('/logout', 'AuthController::logout', ['as' => 'auth.logout']);

# Dashboard
$routes->GET('/dashboard', 'DashboardController::index', ['filter' => 'authGuard', 'as' => 'dashboard.index']);

# Dashboard Images
$routes->GET('/dashboard/images', 'ImageController::index', ['filter' => 'authGuard', 'as' => 'images.index']);
$routes->POST('/dashboard/images/upload', 'ImageController::upload', ['filter' => 'authGuard', 'as' => 'images.upload']);
$routes->POST('/dashboard/images/upload/event', 'ImageController::uploadImageEvent', ['filter' => 'authGuard', 'as' => 'images.upload.event']);
$routes->POST('/dashboard/images/upload/date', 'ImageController::uploadImageDate', ['filter' => 'authGuard', 'as' => 'images.upload.date']);
$routes->GET('/dashboard/images/edit/(:num)', 'ImageController::editForm/$1', ['filter' => 'authGuard', 'as' => 'images.edit']);
$routes->POST('/dashboard/images/update/(:num)', 'ImageController::update/$1', ['filter' => 'authGuard', 'as' => 'images.update']);
$routes->GET('/dashboard/images/delete/(:num)', 'ImageController::delete/$1', ['filter' => 'authGuard', 'as' => 'images.delete']);

# Dashboard Events
$routes->GET('/dashboard/events', 'EventController::index', ['filter' => 'authGuard', 'as' => 'events.index']);
$routes->GET('/dashboard/events/create', 'EventController::create', ['filter' => 'authGuard', 'as' => 'events.create']);
$routes->POST('/dashboard/events/store', 'EventController::store', ['filter' => 'authGuard', 'as' => 'events.store']);
$routes->GET('/dashboard/events/edit/(:num)', 'EventController::edit/$1', ['filter' => 'authGuard', 'as' => 'events.edit']);
$routes->POST('/dashboard/events/update/(:num)', 'EventController::update/$1', ['filter' => 'authGuard', 'as' => 'events.update']);
$routes->GET('/dashboard/events/delete/(:num)', 'EventController::delete/$1', ['filter' => 'authGuard', 'as' => 'events.delete']);
# Ajax
$routes->POST('/dashboard/events/(:num)/attach/images', 'EventController::attachImages/$1', ['filter' => 'authGuard', 'as' => 'events.attach_images']);
$routes->GET('/dashboard/events/(:num)/load-images', 'EventController::loadImages/$1', ['filter' => 'authGuard', 'as' => 'events.load_images']);

# Dashboard Dates
$routes->POST('/dashboard/dates/store', 'DateController::store', ['filter' => 'authGuard', 'as' => 'dates.store']);
$routes->GET('/dashboard/dates/edit/(:num)', 'DateController::editForm/$1', ['filter' => 'authGuard', 'as' => 'dates.edit']);
$routes->POST('/dashboard/dates/update/(:num)', 'DateController::update/$1', ['filter' => 'authGuard', 'as' => 'dates.update']);
$routes->GET('/dashboard/dates/delete/(:num)', 'DateController::delete/$1', ['filter' => 'authGuard', 'as' => 'dates.delete']);
#Ajax
$routes->GET('/dashboard/dates/(:num)/load-images', 'DateController::loadImages/$1', ['filter' => 'authGuard', 'as' => 'dates.load_images_with_id']);
$routes->GET('/dashboard/dates/load-images', 'DateController::loadImages', ['filter' => 'authGuard', 'as' => 'dates.load_images']);
