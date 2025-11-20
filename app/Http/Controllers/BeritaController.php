<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    public function index()
    {
        $beritas = Berita::orderByDesc('published_at')->orderByDesc('created_at')->get();
        $petugas = auth()->guard('petugas')->user();
        return view('admin.berita.index', compact('beritas', 'petugas'));
    }

    public function create()
    {
        $petugas = auth()->guard('petugas')->user();
        return view('admin.berita.create', compact('petugas'));
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
        $validated['slug'] = Str::slug($validated['title']);

        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $path = $file->storeAs('berita', $filename, 'public');
            $validated['cover_image'] = $filename;
        }

        Berita::create($validated);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Berita berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $berita = Berita::findOrFail($id);
        $petugas = auth()->guard('petugas')->user();
        return view('admin.berita.edit', compact('berita', 'petugas'));
    }

    public function update(Request $request, $id)
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

        $berita = Berita::findOrFail($id);
        $validated['slug'] = Str::slug($validated['title']);

        if ($request->hasFile('cover_image')) {
            // Delete old image
            if ($berita->cover_image && Storage::disk('public')->exists('berita/' . $berita->cover_image)) {
                Storage::disk('public')->delete('berita/' . $berita->cover_image);
            }
            
            $file = $request->file('cover_image');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $path = $file->storeAs('berita', $filename, 'public');
            $validated['cover_image'] = $filename;
        }

        $berita->update($validated);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Berita berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);
        // Delete cover image if exists
        if ($berita->cover_image && Storage::disk('public')->exists('berita/' . $berita->cover_image)) {
            Storage::disk('public')->delete('berita/' . $berita->cover_image);
        }
        
        $berita->delete();
        return redirect()->route('admin.dashboard')
            ->with('success', 'Berita berhasil dihapus!');
    }
}

