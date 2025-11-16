<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Download;
use App\Models\Foto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DownloadController extends Controller
{
    public function download(Request $request, Foto $foto)
    {
        // catat download
        Download::create([
            'user_id' => $request->user()->id,
            'foto_id' => $foto->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // tentukan path file
        $file = $foto->file;
        $paths = [
            public_path('images/' . $file),
            storage_path('app/public/foto/' . $file),
            storage_path('app/public/' . $file),
        ];

        foreach ($paths as $path) {
            if ($file && file_exists($path)) {
                return response()->download($path, basename($path));
            }
        }

        return response()->json(['message' => 'File tidak ditemukan'], 404);
    }
}
