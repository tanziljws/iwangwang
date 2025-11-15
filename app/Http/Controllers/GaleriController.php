<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use App\Models\Kategori;
use App\Models\Foto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GaleriController extends Controller
{
    public function index()
    {
        $galeris = Galeri::with(['kategori', 'foto'])->ordered()->get();
        $petugas = auth()->guard('petugas')->user();
        return view('admin.galeri.index', compact('galeris', 'petugas'));
    }

    public function create()
    {
        $kategoris = Kategori::active()->ordered()->get();
        $petugas = auth()->guard('petugas')->user();
        return view('admin.galeri.create', compact('kategoris', 'petugas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'        => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
            'kategori_id' => 'required|exists:kategori,id',
            'urutan'      => 'nullable|integer|min:0',
            'foto.*'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Simpan galeri
        $galeri = Galeri::create([
            'nama'        => $request->nama,
            'deskripsi'   => $request->deskripsi,
            'kategori_id' => $request->kategori_id,
            'urutan'      => $request->urutan ?? 0,
            'status'      => 1
        ]);

        // Save photos if any
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $key => $file) {
                // Generate a unique filename
                $filename = time() . '_' . $file->getClientOriginalName();
                // Store the file in storage/app/public/foto
                $path = $file->storeAs('foto', $filename, 'public');
                
                // Create photo record
                Foto::create([
                    'galeri_id' => $galeri->id,
                    'judul'     => $request->nama,
                    'file'      => basename($path),
                    'urutan'    => $key,
                    'status'    => 1
                ]);
            }
        }

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Galeri & foto berhasil ditambahkan!');
    }

    public function edit(Galeri $galeri)
    {
        $kategoris = Kategori::active()->ordered()->get();
        $petugas = auth()->guard('petugas')->user();
        return view('admin.galeri.edit', compact('galeri', 'kategoris', 'petugas'));
    }

    public function update(Request $request, Galeri $galeri)
    {
        $request->validate([
            'nama'        => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
            'kategori_id' => 'required|exists:kategori,id',
            'urutan'      => 'nullable|integer|min:0',
            'status'      => 'boolean',
            'foto.*'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $galeri->update([
            'nama'        => $request->nama,
            'deskripsi'   => $request->deskripsi,
            'kategori_id' => $request->kategori_id,
            'urutan'      => $request->urutan ?? 0,
            'status'      => $request->status ?? 1
        ]);

        // Tambahkan foto baru kalau ada
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $key => $file) {
                $path = $file->store('foto', 'public');
                Foto::create([
                    'galeri_id' => $galeri->id,
                    'judul'     => $request->nama,
                    'file'      => basename($path),
                    'urutan'    => $key,
                    'status'    => 1
                ]);
            }
        }

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Galeri berhasil diperbarui!');
    }

    public function destroy(Galeri $galeri)
    {
        // Hapus semua foto di storage
        foreach ($galeri->foto as $foto) {
            Storage::disk('public')->delete('foto/' . $foto->file);
            $foto->delete();
        }

        $galeri->delete();

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Galeri & foto berhasil dihapus!');
    }

    public function toggleStatus(Galeri $galeri)
    {
        $galeri->update(['status' => !$galeri->status]);
        $status = $galeri->status ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.galeri.index')
            ->with('success', "Galeri berhasil {$status}!");
    }

    public function show(Galeri $galeri)
    {
        $galeri->load(['kategori', 'foto']);
        $petugas = auth()->guard('petugas')->user();
        return view('admin.galeri.show', compact('galeri', 'petugas'));
    }
}
