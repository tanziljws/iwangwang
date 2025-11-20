<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::ordered()->get();
        $petugas = auth()->guard('petugas')->user();
        return view('admin.kategori.index', compact('kategoris', 'petugas'));
    }

    // API: return JSON list for frontend usage
    public function indexApi()
    {
        $kategoris = Kategori::ordered()->get();
        return response()->json($kategoris, 200);
    }

    public function create()
    {
        $petugas = auth()->guard('petugas')->user();
        return view('admin.kategori.create', compact('petugas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'urutan' => 'nullable|integer|min:0'
        ]);

        $kategori = Kategori::create([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama),
            'deskripsi' => $request->deskripsi,
            'icon' => $request->icon,
            'urutan' => $request->urutan ?? 0,
            'status' => 1
        ]);

        // Jika request datang dari API, balas JSON agar tidak redirect (menghindari CORS/HTML)
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json(['message' => 'Kategori berhasil ditambahkan', 'data' => $kategori], 201);
        }

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit(Kategori $kategori)
    {
        $petugas = auth()->guard('petugas')->user();
        return view('admin.kategori.edit', compact('kategori', 'petugas'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'urutan' => 'nullable|integer|min:0',
            'status' => 'boolean'
        ]);

        $kategori->update([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama),
            'deskripsi' => $request->deskripsi,
            'icon' => $request->icon,
            'urutan' => $request->urutan ?? 0,
            'status' => $request->status ?? 1
        ]);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Request $request, Kategori $kategori)
    {
        // Check if kategori has related galeri
        if ($kategori->galeri()->count() > 0) {
            // Jika dipanggil via API, balas JSON agar tidak redirect (menghindari CORS)
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Kategori tidak dapat dihapus karena masih memiliki galeri!',
                ], 422);
            }

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Kategori tidak dapat dihapus karena masih memiliki galeri!',
                ], 422);
            }
            return redirect()->route('admin.dashboard')
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki galeri!');
        }

        $kategori->delete();

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'Kategori berhasil dihapus!',
            ], 200);
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'Kategori berhasil dihapus!');
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'Kategori berhasil dihapus!',
            ], 200);
        }

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }

    public function toggleStatus(Kategori $kategori)
    {
        $kategori->update(['status' => !$kategori->status]);
        
        $status = $kategori->status ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('admin.kategori.index')
            ->with('success', "Kategori berhasil {$status}!");
    }
}
