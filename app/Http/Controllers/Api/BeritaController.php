<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    public function index()
    {
        try {
            $berita = Berita::where('status', 'published')
                ->orderByDesc('published_at')
                ->orderByDesc('created_at')
                ->get()
                ->map(function ($item) {
                    // Add cover_image_url to response
                    $item->cover_image_url = $item->cover_image_url;
                    return $item;
                });
            return response()->json($berita, 200);
        } catch (\Exception $e) {
            \Log::error('Berita API Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch berita',
                'message' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'author' => 'nullable|string|max:100',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'published_at' => 'nullable|date',
            'status' => 'nullable|in:draft,published',
            'cover_image' => 'nullable|image|max:10240',
        ]);

        $validated['status'] = $validated['status'] ?? 'published';
        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(4);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('berita', 'public');
        }

        $berita = Berita::create($validated);
        return response()->json($berita, 201);
    }

    public function show(Berita $beritum)
    {
        return response()->json($beritum, 200);
    }

    public function update(Request $request, Berita $beritum)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'category' => 'nullable|string|max:100',
            'author' => 'nullable|string|max:100',
            'excerpt' => 'nullable|string',
            'content' => 'sometimes|required|string',
            'published_at' => 'nullable|date',
            'status' => 'nullable|in:draft,published',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('cover_image')) {
            if ($beritum->cover_image) {
                Storage::disk('public')->delete($beritum->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('berita', 'public');
        }

        $beritum->update($validated);
        return response()->json($beritum);
    }

    public function destroy(Berita $beritum)
    {
        if ($beritum->cover_image) {
            Storage::disk('public')->delete($beritum->cover_image);
        }
        $beritum->delete();

        return response()->json(['message' => 'Berita deleted']);
    }
}
