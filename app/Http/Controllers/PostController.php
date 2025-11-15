<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['kategori', 'petugas', 'galeries'])->get();
        return response()->json($posts, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'isi' => 'required',
            'petugas_id' => 'required|exists:petugas,id',
            'status' => 'nullable|in:draft,published'
        ]);

        // default status kalau kosong
        if (!isset($validated['status'])) {
            $validated['status'] = 'draft';
        }

        $post = Post::create($validated);
        return response()->json($post, 201);
    }

    public function show($id)
    {
        $post = Post::with(['kategori', 'petugas', 'galeries'])->findOrFail($id);
        return response()->json($post, 200);
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'sometimes|required|string|max:255',
            'kategori_id' => 'sometimes|required|exists:kategori,id',
            'isi' => 'sometimes|required',
            'petugas_id' => 'sometimes|required|exists:petugas,id',
            'status' => 'nullable|in:draft,published'
        ]);

        $post->update($validated);
        return response()->json($post, 200);
    }

    public function destroy($id)
    {
        Post::findOrFail($id)->delete();
        return response()->json(['message' => 'Post berhasil dihapus']);
    }
}
