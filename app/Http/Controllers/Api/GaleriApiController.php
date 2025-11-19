<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Galeri;

class GaleriApiController extends Controller
{
    public function index()
    {
        try {
            // Get galeri with basic query - no relationships
            $items = Galeri::where('status', 1)
                ->orderBy('urutan')
                ->orderBy('nama')
                ->get(['id', 'nama', 'deskripsi', 'kategori_id', 'status', 'urutan', 'created_at', 'updated_at']);
            
            // Transform to array and manually load relationships
            $result = [];
            foreach ($items as $item) {
                try {
                    $data = [
                        'id' => $item->id,
                        'nama' => $item->nama,
                        'deskripsi' => $item->deskripsi,
                        'kategori_id' => $item->kategori_id,
                        'status' => $item->status,
                        'urutan' => $item->urutan,
                        'created_at' => $item->created_at,
                        'updated_at' => $item->updated_at,
                    ];
                    
                    // Manually load kategori
                    if ($item->kategori_id) {
                        try {
                            $kategori = \App\Models\Kategori::find($item->kategori_id, ['id', 'nama', 'slug', 'deskripsi', 'icon']);
                            $data['kategori'] = $kategori ? [
                                'id' => $kategori->id,
                                'nama' => $kategori->nama,
                                'slug' => $kategori->slug,
                                'deskripsi' => $kategori->deskripsi,
                                'icon' => $kategori->icon,
                            ] : null;
                        } catch (\Exception $e) {
                            Log::warning('Failed to load kategori for galeri ' . $item->id . ': ' . $e->getMessage());
                            $data['kategori'] = null;
                        }
                    } else {
                        $data['kategori'] = null;
                    }
                    
                    // Manually load foto with file_url
                    try {
                        $fotos = \App\Models\Foto::where('galeri_id', $item->id)
                            ->where('status', 1)
                            ->orderBy('urutan')
                            ->orderBy('judul')
                            ->get(['id', 'galeri_id', 'judul', 'deskripsi', 'file', 'alt_text', 'urutan', 'status']);
                        
                        $data['foto'] = $fotos->map(function ($foto) {
                            $fotoData = $foto->toArray();
                            // Add file_url
                            $fotoData['file_url'] = $foto->file_url;
                            return $fotoData;
                        })->toArray();
                    } catch (\Exception $e) {
                        Log::warning('Failed to load foto for galeri ' . $item->id . ': ' . $e->getMessage());
                        $data['foto'] = [];
                    }
                    
                    $result[] = $data;
                } catch (\Exception $e) {
                    Log::error('Error processing galeri ' . $item->id . ': ' . $e->getMessage());
                    // Add basic data even if relationships fail
                    $result[] = [
                        'id' => $item->id,
                        'nama' => $item->nama,
                        'deskripsi' => $item->deskripsi,
                        'kategori_id' => $item->kategori_id,
                        'status' => $item->status,
                        'urutan' => $item->urutan,
                        'kategori' => null,
                        'foto' => [],
                    ];
                }
            }
            
            return response()->json($result, 200);
        } catch (\Exception $e) {
            Log::error('Galeri API Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Failed to fetch galeri',
                'message' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori_id' => 'required|exists:kategori,id',
            'status' => 'nullable|boolean',
            'urutan' => 'nullable|integer|min:0',
        ]);

        if (!isset($validated['status'])) {
            $validated['status'] = 1;
        }

        $galeri = Galeri::create([
            'nama' => $validated['nama'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'kategori_id' => $validated['kategori_id'],
            'status' => $validated['status'],
            'urutan' => $validated['urutan'] ?? 0,
        ]);

        return response()->json($galeri, 201);
    }

    public function show($id)
    {
        $galeri = Galeri::with(['kategori','foto'])->findOrFail($id);
        return response()->json($galeri, 200);
    }

    public function update(Request $request, $id)
    {
        $galeri = Galeri::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'sometimes|required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori_id' => 'sometimes|required|exists:kategori,id',
            'status' => 'nullable|boolean',
            'urutan' => 'nullable|integer|min:0',
        ]);
        $galeri->update($validated);
        return response()->json($galeri, 200);
    }

    public function destroy($id)
    {
        Galeri::findOrFail($id)->delete();
        return response()->json(['message' => 'Galeri berhasil dihapus']);
    }
}
