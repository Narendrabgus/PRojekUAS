<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index', ['filter' => 'auth']);

// Rute untuk proses otentikasi
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

// Rute untuk halaman profil dan riwayat transaksi
$routes->get('profile', 'Home::profile', ['filter' => 'auth']);

$routes->group('layanan', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'LayananController::index');
    $routes->post('create', 'LayananController::create');
    $routes->post('edit/(:num)', 'LayananController::edit/$1');
    $routes->get('delete/(:num)', 'LayananController::delete/$1');
});

// Grup rute untuk Riwayat Pilihan (Keranjang)
$routes->group('riwayat', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'TransaksiController::index');
    $routes->post('add', 'TransaksiController::add');
    $routes->get('delete/(:any)', 'TransaksiController::delete/$1');
    $routes->get('clear', 'TransaksiController::clear');
});

// Grup rute untuk proses Checkout
$routes->group('checkout', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'TransaksiController::checkout');
    $routes->post('buy', 'TransaksiController::buy');
    $routes->get('get-location', 'TransaksiController::getLocation');
    $routes->get('get-cost', 'TransaksiController::getCost');
});