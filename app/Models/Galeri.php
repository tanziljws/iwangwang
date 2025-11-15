<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    use HasFactory;

    protected $table = 'galeri';
    
    protected $fillable = [
        'nama',
        'deskripsi',
        'kategori_id',
        'status',
        'urutan'
    ];

    protected $casts = [
        'status' => 'boolean',
        'urutan' => 'integer'
    ];

    // Relationships
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function foto()
    {
        return $this->hasMany(Foto::class, 'galeri_id', 'id')->orderBy('urutan');
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
