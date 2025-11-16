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

    // Terapkan CORS hanya pada route API dan sanctum
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    // Izinkan semua method HTTP (GET, POST, PUT, DELETE, OPTIONS, ...)
    'allowed_methods' => ['*'],

    // Batasi origin ke frontend dev; bisa ditambah origin lain jika perlu
    'allowed_origins' => [
        'http://localhost:5173',
    ],

    'allowed_origins_patterns' => [],

    // Izinkan semua header yang umum dipakai oleh fetch/AJAX
    'allowed_headers' => ['*'],

    // Tidak perlu expose header khusus untuk kasus ini
    'exposed_headers' => [],

    'max_age' => 0,

    // Untuk dev ini tidak butuh cookie kredensial lintas origin
    'supports_credentials' => false,
];
