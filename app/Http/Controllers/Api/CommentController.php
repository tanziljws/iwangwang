<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Foto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;

class CommentController extends Controller
{
    public function index(Foto $foto)
    {
        $comments = Comment::with('user:id,name')
            ->where('foto_id', $foto->id)
            ->latest()
            ->get()
            ->map(function($c){
                // normalisasi body
                $c->body = $c->body ?? $c->content ?? '';
                return $c;
            });
        return response()->json($comments);
    }

    public function store(Request $request, Foto $foto)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $data = [
            'user_id' => $request->user()->id,
            'foto_id' => $foto->id,
            'body' => $validated['body'],
        ];
        // Jika kolom 'content' ada (skema lama), isi juga
        if (Schema::hasColumn('comments', 'content')) {
            $data['content'] = $validated['body'];
        }

        $comment = Comment::create($data);

        $comment->body = $comment->body ?? $comment->content ?? $validated['body'];
        return response()->json($comment->load('user:id,name'), 201);
    }

    public function destroy(Comment $comment, Request $request)
    {
        if ($comment->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $comment->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
