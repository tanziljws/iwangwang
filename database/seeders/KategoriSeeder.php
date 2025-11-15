<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            [
                'nama' => 'Nature',
                'slug' => 'nature',
                'deskripsi' => 'Kategori untuk foto-foto pemandangan alam, gunung, laut, dan keindahan alam lainnya.',
                'icon' => 'fas fa-mountain',
                'urutan' => 1,
                'status' => 1
            ],
            [
                'nama' => 'Urban',
                'slug' => 'urban',
                'deskripsi' => 'Kategori untuk foto-foto kehidupan kota, arsitektur modern, dan suasana urban.',
                'icon' => 'fas fa-city',
                'urutan' => 2,
                'status' => 1
            ],
            [
                'nama' => 'Portrait',
                'slug' => 'portrait',
                'deskripsi' => 'Kategori untuk foto-foto portrait manusia, ekspresi, dan karakteristik wajah.',
                'icon' => 'fas fa-user',
                'urutan' => 3,
                'status' => 1
            ],
            [
                'nama' => 'Architecture',
                'slug' => 'architecture',
                'deskripsi' => 'Kategori untuk foto-foto bangunan, desain arsitektur, dan struktur yang menakjubkan.',
                'icon' => 'fas fa-building',
                'urutan' => 4,
                'status' => 1
            ],
            [
                'nama' => 'Events',
                'slug' => 'events',
                'deskripsi' => 'Kategori untuk foto-foto acara, perayaan, dan momen-momen spesial.',
                'icon' => 'fas fa-calendar-alt',
                'urutan' => 5,
                'status' => 1
            ]
        ];

        foreach ($kategoris as $kategori) {
            Kategori::create($kategori);
        }
    }
}
