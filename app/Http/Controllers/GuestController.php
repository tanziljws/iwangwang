<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Galeri;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function home()
    {
        $kategoris = Kategori::active()->ordered()->get();
        $galeris = Galeri::active()
            ->with([
                'kategori',
                'foto' => function ($query) {
                    $query->active()->ordered();
                }
            ])
            ->ordered()
            ->get();
        
        // Hitung total foto
        $totalFotos = $galeris->sum(function ($galeri) {
            return $galeri->foto->count();
        });
        
        return view('guest.home-new', compact('kategoris', 'galeris', 'totalFotos'));
    }

    public function tentang()
    {
        return view('guest.tentang');
    }

    public function informasi()
    {
        return view('guest.informasi');
    }

    public function gallery()
    {
        $kategoris = Kategori::active()->ordered()->get();
        $galeris = Galeri::active()
            ->with([
                'kategori',
                'foto' => function ($query) {
                    $query->active()->ordered();
                }
            ])
            ->ordered()
            ->get();
        
        // Hitung total foto
        $totalFotos = $galeris->sum(function ($galeri) {
            return $galeri->foto->count();
        });
        
        return view('guest.gallery', compact('kategoris', 'galeris', 'totalFotos'));
    }

    public function kontak()
    {
        return view('guest.kontak');
    }

    public function galeri()
    {
        $kategoris = Kategori::active()->ordered()->get();
        $galeris = Galeri::active()
            ->with([
                'kategori',
                'foto' => function ($query) {
                    $query->active()->ordered();
                }
            ])
            ->ordered()
            ->get();
        
        // Hitung total foto
        $totalFotos = $galeris->sum(function ($galeri) {
            return $galeri->foto->count();
        });
        
        return view('guest.galeri', compact('kategoris', 'galeris', 'totalFotos'));
    }
}
