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
            return $this->getBaseUrl() . '/images/placeholder.jpg';
        }
        
        // If it's already a full URL
        if (strpos($this->file, 'http') === 0) {
            return $this->file;
        }
        
        $baseUrl = $this->getBaseUrl();
        
        // Check if file exists in storage/app/public/foto/
        if (Storage::disk('public')->exists('foto/' . $this->file)) {
            return $baseUrl . '/storage/foto/' . $this->file;
        }
        
        // Check if file exists directly in storage/app/public/
        if (Storage::disk('public')->exists($this->file)) {
            return $baseUrl . '/storage/' . $this->file;
        }
        
        // Check if file exists in public/images
        if (file_exists(public_path('images/' . $this->file))) {
            return $baseUrl . '/images/' . $this->file;
        }
        
        // Fallback - return URL even if file doesn't exist (for Railway)
        return $baseUrl . '/storage/foto/' . $this->file;
    }
    
    /**
     * Get base URL from request or config
     */
    private function getBaseUrl()
    {
        // Try to get from request first (for API calls)
        if (app()->runningInConsole() === false && request()) {
            $scheme = request()->getScheme();
            $host = request()->getHost();
            $port = request()->getPort();
            
            if ($port && !in_array($port, [80, 443])) {
                return $scheme . '://' . $host . ':' . $port;
            }
            return $scheme . '://' . $host;
        }
        
        // Fallback to config
        $appUrl = config('app.url', 'http://localhost:8000');
        // Remove trailing slash
        return rtrim($appUrl, '/');
    }

    public function getThumbnailUrlAttribute()
    {
        if (empty($this->file)) {
            return $this->getBaseUrl() . '/images/placeholder.jpg';
        }
        
        $baseUrl = $this->getBaseUrl();
        $pathInfo = pathinfo($this->file);
        $thumbnailPath = 'foto/thumbnails/' . $pathInfo['filename'] . '_thumb.' . ($pathInfo['extension'] ?? 'jpg');
        
        // Check if thumbnail exists in storage
        if (file_exists(storage_path('app/public/' . $thumbnailPath))) {
            return $baseUrl . '/storage/' . $thumbnailPath;
        }
        
        // Fallback to original image if thumbnail doesn't exist
        return $this->file_url;
    }
}
