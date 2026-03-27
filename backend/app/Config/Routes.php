<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', function() {
    return "home working";
});

$routes->get('test', function() {
    return "test working";
});

$routes->get('check-controller', 'AuthController::testMethod');

// ✅ Main API
$routes->match(['get','post'], 'create-teacher', 'AuthController::createTeacherWithUser');

// ✅ Login API (ADD THIS)
$routes->post('login', 'AuthController::login');
$routes->options('(:any)', 'AuthController::options');
$routes->get('users', 'AuthController::getUsers');
$routes->get('teachers', 'AuthController::getTeachers');