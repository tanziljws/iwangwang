<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Galeri;
use App\Models\Kategori;

class GaleriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = Kategori::all();
        
        if ($kategoris->count() == 0) {
            $this->command->warn('No categories found. Please run KategoriSeeder first.');
            return;
        }

        $galeris = [
            [
                'nama' => 'Galeri Pemandangan Alam',
                'deskripsi' => 'Koleksi foto-foto pemandangan alam yang menakjubkan dari berbagai lokasi.',
                'kategori_id' => $kategoris->where('nama', 'Nature')->first()->id ?? $kategoris->first()->id,
                'urutan' => 1,
                'status' => 1
            ],
            [
                'nama' => 'Galeri Arsitektur Kota',
                'deskripsi' => 'Foto-foto bangunan dan arsitektur modern yang menakjubkan.',
                'kategori_id' => $kategoris->where('nama', 'Architecture')->first()->id ?? $kategoris->first()->id,
                'urutan' => 2,
                'status' => 1
            ],
            [
                'nama' => 'Galeri Portrait Manusia',
                'deskripsi' => 'Koleksi foto portrait yang menangkap ekspresi dan karakteristik manusia.',
                'kategori_id' => $kategoris->where('nama', 'Portrait')->first()->id ?? $kategoris->first()->id,
                'urutan' => 3,
                'status' => 1
            ],
            [
                'nama' => 'Galeri Kehidupan Urban',
                'deskripsi' => 'Foto-foto yang menggambarkan kehidupan kota dan suasana urban.',
                'kategori_id' => $kategoris->where('nama', 'Urban')->first()->id ?? $kategoris->first()->id,
                'urutan' => 4,
                'status' => 1
            ],
            [
                'nama' => 'Galeri Acara Sekolah',
                'deskripsi' => 'Dokumentasi berbagai acara dan kegiatan sekolah yang berkesan.',
                'kategori_id' => $kategoris->where('nama', 'Events')->first()->id ?? $kategoris->first()->id,
                'urutan' => 5,
                'status' => 1
            ]
        ];

        foreach ($galeris as $galeri) {
            Galeri::create($galeri);
        }

        $this->command->info('Sample galleries created successfully!');
    }
}
