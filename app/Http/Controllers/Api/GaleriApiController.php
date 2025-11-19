<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Galeri;

class GaleriApiController extends Controller
{
    public function index()
    {
        try {
            $items = Galeri::with(['kategori', 'foto'])
                ->where('status', 1)
                ->ordered()
                ->get();
            return response()->json($items, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch galeri',
                'message' => $e->getMessage()
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
