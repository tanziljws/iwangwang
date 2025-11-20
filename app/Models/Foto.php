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
            return url('/images/placeholder.jpg');
        }
        
        // If it's already a full URL
        if (strpos($this->file, 'http') === 0) {
            return $this->file;
        }
        
        // Always use absolute path with leading slash
        // Try storage/foto/ first (most common)
        if (Storage::disk('public')->exists('foto/' . $this->file)) {
            return url('/storage/foto/' . $this->file);
        }
        
        // Try storage/ directly
        if (Storage::disk('public')->exists($this->file)) {
            return url('/storage/' . $this->file);
        }
        
        // Try media/foto/ route (for Railway)
        return url('/media/foto/' . $this->file);
    }
    
    /**
     * Get base URL from request or config
     */
    private function getBaseUrl()
    {
        // Try to get from request first (for API calls)
        try {
            if (app()->runningInConsole() === false) {
                $request = request();
                if ($request) {
                    // Use url() helper which respects APP_URL and request
                    $baseUrl = url('/');
                    // Remove trailing slash
                    return rtrim($baseUrl, '/');
                }
            }
        } catch (\Exception $e) {
            // If request is not available, continue to fallback
        }
        
        // Fallback to config - check for Railway URL
        $appUrl = config('app.url', 'http://localhost:8000');
        
        // If APP_URL is still localhost, try to detect from environment
        if (strpos($appUrl, 'localhost') !== false || strpos($appUrl, '127.0.0.1') !== false) {
            // Check if we're in production (Railway)
            if (config('app.env') === 'production' || env('RAILWAY_ENVIRONMENT')) {
                // Try to get from Railway environment variable
                $railwayUrl = env('RAILWAY_PUBLIC_DOMAIN') 
                    ? 'https://' . env('RAILWAY_PUBLIC_DOMAIN')
                    : 'https://iwangwang-production.up.railway.app';
                return rtrim($railwayUrl, '/');
            }
        }
        
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
