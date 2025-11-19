<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Berita extends Model
{
    use HasFactory;

    protected $table = 'berita';

    protected $fillable = [
        'title',
        'slug',
        'category',
        'author',
        'excerpt',
        'content',
        'published_at',
        'cover_image',
        'status',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($berita) {
            if (empty($berita->slug)) {
                $berita->slug = Str::slug($berita->title) . '-' . Str::random(4);
            }
        });

        static::updating(function ($berita) {
            if ($berita->isDirty('title')) {
                $berita->slug = Str::slug($berita->title) . '-' . Str::random(4);
            }
        });
    }

    /**
     * Get the cover image URL
     */
    public function getCoverImageUrlAttribute()
    {
        if (empty($this->cover_image)) {
            return null;
        }

        // If it's already a full URL
        if (strpos($this->cover_image, 'http') === 0) {
            return $this->cover_image;
        }

        // Check if file exists in storage/app/public/berita/
        if (Storage::disk('public')->exists('berita/' . basename($this->cover_image))) {
            return asset('storage/berita/' . basename($this->cover_image));
        }

        // Check if file exists directly in storage/app/public/
        if (Storage::disk('public')->exists($this->cover_image)) {
            return asset('storage/' . $this->cover_image);
        }

        // Fallback - return URL even if file doesn't exist (for Railway)
        // Handle both 'berita/filename.jpg' and just 'filename.jpg'
        $filename = basename($this->cover_image);
        if (strpos($this->cover_image, 'berita/') === 0) {
            return asset('storage/' . $this->cover_image);
        }
        return asset('storage/berita/' . $filename);
    }
}
