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
        $rules = [
            'galeri_id' => 'required|exists:galeri,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            // Naikkan batas ukuran file menjadi 8MB (8192 KB)
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:8192',
            'alt_text' => 'nullable|string|max:255',
            'urutan' => 'nullable|integer|min:0'
        ];

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors(),
                ], 422);
            }

            return redirect()->back()->withErrors($validator)->withInput();
        }

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

        // If API request, replace any previous photos in this gallery with this new one
        if ($request->wantsJson() || $request->is('api/*')) {
            Foto::where('galeri_id', $foto->galeri_id)
                ->where('id', '!=', $foto->id)
                ->get()
                ->each(function ($other) {
                    if ($other->file && Storage::disk('public')->exists('foto/' . $other->file)) {
                        Storage::disk('public')->delete('foto/' . $other->file);
                    }
                    $other->delete();
                });

            return response()->json([
                'message' => 'Foto berhasil ditambahkan',
                'data' => $foto->fresh(),
            ], 201);
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

        $rules = [
            // Untuk update dari API, field ini bisa dikirim sebagian; gunakan sometimes|required
            'galeri_id' => 'sometimes|required|exists:galeri,id',
            'judul' => 'sometimes|required|string|max:255',
            'deskripsi' => 'nullable|string',
            // Samakan batas ukuran dengan store (8MB)
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:8192',
            'alt_text' => 'nullable|string|max:255',
            'urutan' => 'nullable|integer|min:0',
            'status' => 'sometimes|boolean',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors(),
                ], 422);
            }

            return redirect()->back()->withErrors($validator)->withInput();
        }

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

        // Jika dipanggil via API, jadikan foto ini sebagai satu-satunya foto galeri (replace semua yang lama)
        if ($request->wantsJson() || $request->is('api/*')) {
            // Hapus semua foto lain dalam galeri yang berbeda id-nya
            Foto::where('galeri_id', $foto->galeri_id)
                ->where('id', '!=', $foto->id)
                ->get()
                ->each(function ($other) {
                    if ($other->file && Storage::disk('public')->exists('foto/' . $other->file)) {
                        Storage::disk('public')->delete('foto/' . $other->file);
                    }
                    $other->delete();
                });

            return response()->json([
                'message' => 'Foto berhasil diperbarui',
                'data' => $foto->fresh(),
            ], 200);
        }

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
        
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json(['success' => 'Foto berhasil dihapus.']);
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'Foto berhasil dihapus!');
    }

    public function toggleStatus(Foto $foto)
    {
        $foto->update(['status' => !$foto->status]);
        
        $status = $foto->status ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('admin.foto.index')
            ->with('success', "Foto berhasil {$status}!");
    }
}
