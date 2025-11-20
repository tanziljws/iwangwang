<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Download;
use App\Models\Foto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Throwable;

class DownloadController extends Controller
{
    public function download(Request $request, Foto $foto)
    {
        // catat download (jangan sampai error DB membuat download gagal)
        try {
            // Support both Sanctum token and session-based auth
            $user = $request->user();
            if (!$user) {
                $user = auth('web')->user();
            }
            if ($user) {
                Download::create([
                    'user_id' => $user->id,
                    'foto_id' => $foto->id,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            }
        } catch (Throwable $e) {
            // optional: log error, tapi jangan hentikan proses download
            // logger()->error('Download log failed', ['error' => $e->getMessage()]);
        }

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
