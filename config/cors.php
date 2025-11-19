<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi ini mengizinkan permintaan dari frontend Vite
    | (mis. http://localhost:5173) ke endpoint API Laravel (/api/*),
    | termasuk method DELETE, tanpa error CORS.
    */

    // Terapkan CORS pada semua route (gunakan wildcard untuk semua path)
    'paths' => ['*'],

    // Izinkan semua method HTTP (GET, POST, PUT, DELETE, OPTIONS, ...)
    'allowed_methods' => ['*'],

    // Izinkan origin dari frontend dev dan production Railway
    'allowed_origins' => [
        'http://localhost:5173',
        'https://iwangtgallery3.up.railway.app',
        'http://iwangtgallery3.up.railway.app',
    ],

    'allowed_origins_patterns' => [],

    // Izinkan semua header yang umum dipakai oleh fetch/AJAX
    'allowed_headers' => ['*'],

    // Tidak perlu expose header khusus untuk kasus ini
    'exposed_headers' => [],

    'max_age' => 0,

    // Izinkan credentials untuk authentication
    'supports_credentials' => true,
];
