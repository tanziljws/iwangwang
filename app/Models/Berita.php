<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Request;

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

        // Get base URL from request or config
        $baseUrl = $this->getBaseUrl();
        
        // Handle both 'berita/filename.jpg' and just 'filename.jpg'
        $filename = basename($this->cover_image);
        $path = 'storage/berita/' . $filename;
        
        if (strpos($this->cover_image, 'berita/') === 0) {
            $path = 'storage/' . $this->cover_image;
        }
        
        return $baseUrl . '/' . ltrim($path, '/');
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
}
