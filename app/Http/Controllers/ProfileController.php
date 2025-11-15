<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    // Ambil data pertama dari tabel profile
    public function show()
    {
        return response()->json(Profile::first());
    }

    // Update atau buat data pertama
    public function update(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi'   => 'required|string',
        ]);

        $profile = Profile::first();

        if (!$profile) {
            $profile = Profile::create($request->only(['judul', 'isi']));
        } else {
            $profile->update($request->only(['judul', 'isi']));
        }

        return response()->json($profile);
    }
}
