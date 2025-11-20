<?php

/**
 * Script untuk membuat admin user
 * 
 * Usage: php create-admin.php
 * 
 * Atau dengan custom credentials:
 * php create-admin.php --username=admin --password=admin123 --email=admin@smkn4-bogor.sch.id
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Petugas;
use Illuminate\Support\Facades\Hash;

// Parse command line arguments
$options = getopt('', ['username:', 'password:', 'email:', 'name:']);

$username = $options['username'] ?? 'admin';
$password = $options['password'] ?? 'admin123';
$email = $options['email'] ?? 'admin@smkn4-bogor.sch.id';
$name = $options['name'] ?? 'Administrator';

// Check if admin already exists
$existing = Petugas::where('username', $username)->orWhere('email', $email)->first();

if ($existing) {
    echo "❌ Admin dengan username '{$username}' atau email '{$email}' sudah ada!\n";
    echo "Username: {$existing->username}\n";
    echo "Email: {$existing->email}\n";
    echo "Jabatan: {$existing->jabatan}\n";
    echo "Status: {$existing->status}\n";
    exit(1);
}

// Create admin
try {
    $admin = Petugas::create([
        'nama_petugas' => $name,
        'username' => $username,
        'password' => Hash::make($password),
        'email' => $email,
        'jabatan' => 'admin',
        'status' => 'aktif',
    ]);

    echo "✅ Admin berhasil dibuat!\n\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "📋 CREDENTIALS ADMIN\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "Username: {$username}\n";
    echo "Password: {$password}\n";
    echo "Email: {$email}\n";
    echo "Nama: {$name}\n";
    echo "Jabatan: admin\n";
    echo "Status: aktif\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "\n💡 Gunakan credentials ini untuk login di /admin/login\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

