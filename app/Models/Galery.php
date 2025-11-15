<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Galery extends Model {
    protected $table = 'galery';
    protected $fillable = ['post_id', 'position', 'status'];

    public function post() {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function fotos() {
        return $this->hasMany(Foto::class, 'galery_id');
    }
}
