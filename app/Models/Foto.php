<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Foto extends Model
{
    use HasFactory;

    protected $table = 'foto';
    
    protected $fillable = [
        'galeri_id',
        'judul',
        'deskripsi',
        'file',
        'alt_text',
        'urutan',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
        'urutan' => 'integer'
    ];

    // Relationships
    public function galeri()
    {
        return $this->belongsTo(Galeri::class);
    }

    public function kategori()
    {
        return $this->hasOneThrough(Kategori::class, Galeri::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan')->orderBy('judul');
    }

    /**
     * Get the photo URL with fallback to placeholder
     *
     * @return string
     */
    public function getPhotoUrlAttribute()
    {
        if ($this->file && Storage::disk('public')->exists('foto/' . $this->file)) {
            return asset('storage/foto/' . $this->file);
        }
        
        // Return a default placeholder image if no photo exists
        return asset('images/placeholder.jpg');
    }
    
    // Accessors
    public function getFileUrlAttribute()
    {
        if (empty($this->file)) {
            return asset('images/placeholder.jpg'); // Fallback image
        }
        
        // If it's already a full URL
        if (strpos($this->file, 'http') === 0) {
            return $this->file;
        }
        
        // Check if file exists in storage/app/public/foto/
        if (Storage::disk('public')->exists('foto/' . $this->file)) {
            return asset('storage/foto/' . $this->file);
        }
        
        // Check if file exists directly in storage/app/public/
        if (Storage::disk('public')->exists($this->file)) {
            return asset('storage/' . $this->file);
        }
        
        // Check if file exists in public/images
        if (file_exists(public_path('images/' . $this->file))) {
            return asset('images/' . $this->file);
        }
        
        // Fallback - return URL even if file doesn't exist (for Railway)
        return asset('storage/foto/' . $this->file);
    }

    public function getThumbnailUrlAttribute()
    {
        if (empty($this->file)) {
            return asset('images/placeholder.jpg'); // Fallback image
        }
        
        $pathInfo = pathinfo($this->file);
        $thumbnailPath = 'foto/thumbnails/' . $pathInfo['filename'] . '_thumb.' . ($pathInfo['extension'] ?? 'jpg');
        
        // Check if thumbnail exists in storage
        if (file_exists(storage_path('app/public/' . $thumbnailPath))) {
            return asset('storage/' . $thumbnailPath);
        }
        
        // Fallback to original image if thumbnail doesn't exist
        return $this->file_url;
    }
}
