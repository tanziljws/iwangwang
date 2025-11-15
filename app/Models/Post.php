<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $fillable = [
        'judul',
        'kategori_id',
        'isi',
        'petugas_id',
        'status'
    ];

    /**
     * Relasi ke tabel kategori (Many-to-One)
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    /**
     * Relasi ke tabel petugas (Many-to-One)
     */
    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'petugas_id');
    }

    /**
     * Relasi ke tabel galery (One-to-Many)
     */
    public function galeries()
    {
        return $this->hasMany(Galery::class, 'post_id');
    }
}
