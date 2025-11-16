<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Foto;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(Request $request, Foto $foto)
    {
        $userId = $request->user()->id;

        $like = Like::where('user_id', $userId)
            ->where('foto_id', $foto->id)
            ->first();

        if ($like) {
            $like->delete();
            $status = 'unliked';
        } else {
            $like = Like::create([
                'user_id' => $userId,
                'foto_id' => $foto->id,
            ]);
            $status = 'liked';
        }

        return response()->json([
            'status' => $status,
            'count' => Like::where('foto_id', $foto->id)->count(),
        ]);
    }

    public function count(Foto $foto)
    {
        $count = Like::where('foto_id', $foto->id)->count();
        return response()->json(['count' => $count]);
    }
}
