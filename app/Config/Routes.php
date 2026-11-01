<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Public pages: anyone can view these
$routes->get('/', 'Home::index');
$routes->get('tasks', 'Tasks::index');
$routes->get('profile', 'Profile::index');
$routes->get('about', 'About::index');

// Login and logout
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attempt');
$routes->post('logout', 'Auth::logout');

// Protected actions: the "auth" filter redirects to /login when logged out
$routes->get('tasks/new', 'Tasks::new', ['filter' => 'auth']);
$routes->post('tasks', 'Tasks::create', ['filter' => 'auth']);
$routes->get('tasks/(:num)/edit', 'Tasks::edit/$1', ['filter' => 'auth']);
$routes->post('tasks/(:num)/update', 'Tasks::update/$1', ['filter' => 'auth']);
$routes->post('tasks/(:num)/delete', 'Tasks::delete/$1', ['filter' => 'auth']);
