<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';
    
    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
        'icon',
        'status',
        'urutan'
    ];

    protected $casts = [
        'status' => 'boolean',
        'urutan' => 'integer'
    ];

    // Relationships
    public function galeri()
    {
        return $this->hasMany(Galeri::class);
    }

    public function foto()
    {
        return $this->hasManyThrough(Foto::class, Galeri::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan')->orderBy('nama');
    }
}
