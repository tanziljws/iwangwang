<?php

namespace App\Http\Controllers;

use App\Models\Galery;
use Illuminate\Http\Request;

class GaleryController extends Controller
{
    public function index()
    {
        $galery = Galery::with(['post', 'fotos'])->get();
        return response()->json($galery, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'position' => 'nullable|integer',
            'status' => 'boolean'
        ]);

        $galery = Galery::create($request->all());
        return response()->json($galery, 201);
    }

    public function show($id)
    {
        $galery = Galery::with(['post', 'fotos'])->findOrFail($id);
        return response()->json($galery, 200);
    }

    public function update(Request $request, $id)
    {
        $galery = Galery::findOrFail($id);
        $galery->update($request->all());
        return response()->json($galery, 200);
    }

    public function destroy($id)
    {
        Galery::findOrFail($id)->delete();
        return response()->json(['message' => 'Galeri berhasil dihapus']);
    }
}
