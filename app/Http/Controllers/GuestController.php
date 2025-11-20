<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Galeri;
use App\Models\Berita;
use App\Models\Agenda;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function home()
    {
        return view('guest.home');
    }

    public function berita()
    {
        $berita = Berita::where('status', 'published')
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->get();
        
        return view('guest.berita', compact('berita'));
    }

    public function beritaShow($id)
    {
        $berita = Berita::where('status', 'published')->findOrFail($id);
        return view('guest.berita-detail', compact('berita'));
    }

    public function agenda()
    {
        $agendas = Agenda::orderBy('date')->latest()->get();
        return view('guest.agenda', compact('agendas'));
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
