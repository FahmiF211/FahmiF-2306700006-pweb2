<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('games', 'GameController::index');
$routes->get('games/detail/(:num)', 'GameController::detail/$1');
$routes->get('game/(:num)', 'GameController::detail/$1');
$routes->get('favorites', 'Home::favorites');
$routes->post('games/favorite', 'Home::addFavorite');
$routes->post('games/favorite/remove', 'Home::removeFavorite');
$routes->post('games/review', 'Home::addReview');
$routes->post('games/review/update', 'Home::updateReview');
$routes->post('games/review/delete', 'Home::deleteReview');

$routes->match(['get', 'post'], 'login', 'Auth::login');
$routes->get('auth/google', 'Auth::google');
$routes->get('auth/google/callback', 'Auth::googleCallback');
$routes->match(['get', 'post'], 'register', 'AuthController::register');
$routes->get('logout', 'Auth::logout');
$routes->get('profile', 'ProfileController::index');
$routes->post('profile/update', 'ProfileController::update');

$routes->get('admin/dashboard', 'AdminController::dashboard');
$routes->get('admin/users', 'AdminController::users');
$routes->post('admin/users/role', 'AdminController::updateUserRole');
$routes->post('admin/users/delete', 'AdminController::deleteUser');
$routes->get('admin/reviews', 'AdminController::reviews');
$routes->post('admin/reviews/delete', 'AdminController::deleteReview');
$routes->get('admin/banners', 'AdminController::banners');
$routes->post('admin/banners', 'AdminController::createBanner');
$routes->post('admin/banners/update/(:num)', 'AdminController::updateBanner/$1');
$routes->post('admin/banners/delete', 'AdminController::deleteBanner');
