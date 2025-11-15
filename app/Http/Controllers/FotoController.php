<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FotoController extends Controller
{
    public function index()
    {
        $fotos = Foto::with(['galeri.kategori'])->ordered()->get();
        $petugas = auth()->guard('petugas')->user();
        return view('admin.foto.index', compact('fotos', 'petugas'));
    }

    public function create()
    {
        $galeris = Galeri::active()->with('kategori')->ordered()->get();
        $petugas = auth()->guard('petugas')->user();
        return view('admin.foto.create', compact('galeris', 'petugas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'galeri_id' => 'required|exists:galeri,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'alt_text' => 'nullable|string|max:255',
            'urutan' => 'nullable|integer|min:0'
        ]);

        $filename = null;
        
        // Store the file in storage/app/public/foto
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            // Generate a unique filename
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            // Store the file in the storage/app/public/foto directory
            $path = $file->storeAs('foto', $filename, 'public');
        }

        // Create the photo record
        $foto = Foto::create([
            'galeri_id' => $request->galeri_id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'file' => $filename,
            'alt_text' => $request->alt_text ?? $request->judul,
            'urutan' => $request->urutan ?? 0,
            'status' => 1
        ]);

        // If API request, return JSON to avoid redirect and confirm file name
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json(['message' => 'Foto berhasil ditambahkan', 'data' => $foto], 201);
        }

        return redirect()->route('admin.foto.index')
            ->with('success', 'Foto berhasil ditambahkan!');
    }

    public function edit(Foto $foto)
    {
        $galeris = Galeri::active()->with('kategori')->ordered()->get();
        $petugas = auth()->guard('petugas')->user();
        return view('admin.foto.edit', compact('foto', 'galeris', 'petugas'));
    }

    public function update(Request $request, $id)
    {
        $foto = Foto::findOrFail($id);
        
        $request->validate([
            'galeri_id' => 'required|exists:galeri,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'alt_text' => 'nullable|string|max:255',
            'urutan' => 'nullable|integer|min:0',
            'status' => 'required|boolean'
        ]);

        $data = $request->except('file');

        // Update photo if a new file is uploaded
        if ($request->hasFile('file')) {
            // Delete old file if it exists
            if ($foto->file && Storage::disk('public')->exists('foto/' . $foto->file)) {
                Storage::disk('public')->delete('foto/' . $foto->file);
            }
            
            $file = $request->file('file');
            // Generate a unique filename
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            // Store the new file
            $path = $file->storeAs('foto', $filename, 'public');
            $data['file'] = $filename;
        }

        $foto->update($data);

        return redirect()->route('admin.foto.index')
            ->with('success', 'Foto berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $foto = Foto::findOrFail($id);
        
        // Delete the file from storage
        if ($foto->file && Storage::disk('public')->exists('foto/' . $foto->file)) {
            Storage::disk('public')->delete('foto/' . $foto->file);
        }
        
        $foto->delete();
        
        return response()->json(['success' => 'Foto berhasil dihapus.']);
    }

    public function toggleStatus(Foto $foto)
    {
        $foto->update(['status' => !$foto->status]);
        
        $status = $foto->status ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('admin.foto.index')
            ->with('success', "Foto berhasil {$status}!");
    }
}
