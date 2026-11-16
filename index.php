<?php
// index.php

// 1. Load Autoload Composer & Configs
require 'vendor/autoload.php';
require 'config/database.php';
require 'config/globals.php';
require 'config/helpers.php';
require 'config/router.php'; // <-- Load router di sini

// 2. Load Models
require 'models/UserModel.php';

use eftec\bladeone\BladeOne;

// 3. Setup Blade Engine Global Function (agar bisa dipakai di dalam route)
function view($viewName, $data = [])
{
    $views = __DIR__ . '/views';
    $cache = __DIR__ . '/cache';
    $blade = new BladeOne($views, $cache, BladeOne::MODE_DEBUG);
    echo $blade->run($viewName, $data);
}

// ==========================================
// 4. DEFINISIKAN ROUTE / URL DI SINI
// ==========================================

// Route untuk Halaman Utama (Homepage)
Router::get('/', function () {
    global $pdo; // Panggil koneksi database global

    $userModel = new UserModel($pdo);
    $daftarUser = $userModel->getAllUsers();

    view('home', [
        'title' => APP_NAME,
        'users' => $daftarUser
    ]);
});

// Route untuk Halaman Tentang Kami (Contoh Halaman Statis)
Router::get('/about', function () {
    view('about', [
        'title' => 'Tentang Kami - ' . APP_NAME
    ]);
});

// Route untuk Proses Simpan Data Form (Method POST)
Router::post('/user/simpan', function () {
    // Logic untuk menyimpan data user ke database ada di sini...
    echo "Proses menyimpan data...";
});


// 5. Jalankan Engine Router
Router::run();
