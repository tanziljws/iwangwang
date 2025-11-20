<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Petugas;
use Illuminate\Support\Facades\Hash;

class PetugasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin already exists
        if (!Petugas::where('username', 'admin')->exists()) {
            Petugas::create([
                'nama_petugas' => 'Administrator',
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'email' => 'admin@smkn4-bogor.sch.id',
                'jabatan' => 'admin',
                'status' => 'aktif',
            ]);
        }

        Petugas::create([
            'nama_petugas' => 'Petugas 1',
            'username' => 'petugas1',
            'password' => Hash::make('petugas123'),
            'email' => 'petugas1@webgallery.com',
            'jabatan' => 'petugas',
            'status' => 'aktif',
        ]);

        Petugas::create([
            'nama_petugas' => 'Petugas 2',
            'username' => 'petugas2',
            'password' => Hash::make('petugas123'),
            'email' => 'petugas2@webgallery.com',
            'jabatan' => 'petugas',
            'status' => 'aktif',
        ]);
    }
}
