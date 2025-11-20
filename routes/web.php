<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\FotoController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\Api\AgendaController;
use App\Http\Controllers\Api\BeritaController;
use App\Http\Controllers\Api\GaleriApiController;
use App\Http\Controllers\UserAuthController;

// Proxy storage files to bypass Apache 403 on symlink
// This route must be early and without middleware to allow public access
Route::get('/storage/{path}', function ($path) {
    try {
        // Decode URL-encoded path
        $path = urldecode($path);
        
        // Security: prevent directory traversal
        if (strpos($path, '..') !== false || strpos($path, '/') === 0) {
            abort(403, 'Invalid path');
        }
        
        // Try multiple possible locations
        $possiblePaths = [
            storage_path('app/public/foto/' . $path),
            storage_path('app/public/' . $path),
            storage_path('app/public/berita/' . $path),
            storage_path('app/public/agenda/' . $path),
        ];
        
        foreach ($possiblePaths as $full) {
            if (file_exists($full) && is_file($full) && is_readable($full)) {
                $mimeType = mime_content_type($full) ?: 'image/jpeg';
                return response()->file($full, [
                    'Content-Type' => $mimeType,
                    'Cache-Control' => 'public, max-age=31536000',
                ]);
            }
        }
        
        // Log for debugging
        \Log::warning('Storage file not found', [
            'path' => $path,
            'tried_paths' => $possiblePaths
        ]);
        
        abort(404, 'File not found: ' . $path);
    } catch (\Exception $e) {
        \Log::error('Storage route error', [
            'path' => $path ?? 'unknown',
            'error' => $e->getMessage()
        ]);
        abort(500, 'Error serving file');
    }
})->where('path', '.*');

// Alternate media route to fully bypass any web server alias restrictions
Route::get('/media/{path}', function ($path) {
    try {
        // Decode URL-encoded path
        $path = urldecode($path);
        
        // Security: prevent directory traversal
        if (strpos($path, '..') !== false || strpos($path, '/') === 0) {
            abort(403, 'Invalid path');
        }
        
        // Try multiple possible locations
        $possiblePaths = [
            storage_path('app/public/foto/' . $path),
            storage_path('app/public/' . $path),
            storage_path('app/public/berita/' . $path),
            storage_path('app/public/agenda/' . $path),
        ];
        
        foreach ($possiblePaths as $full) {
            if (file_exists($full) && is_file($full) && is_readable($full)) {
                $mimeType = mime_content_type($full) ?: 'image/jpeg';
                return response()->file($full, [
                    'Content-Type' => $mimeType,
                    'Cache-Control' => 'public, max-age=31536000',
                ]);
            }
        }
        
        // Log for debugging
        \Log::warning('Media file not found', [
            'path' => $path,
            'tried_paths' => $possiblePaths
        ]);
        
        abort(404, 'File not found: ' . $path);
    } catch (\Exception $e) {
        \Log::error('Media route error', [
            'path' => $path ?? 'unknown',
            'error' => $e->getMessage()
        ]);
        abort(500, 'Error serving file');
    }
})->where('path', '.*');

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ======================= Guest Routes (must be before API routes) =======================
Route::get('/', [GuestController::class, 'home'])->name('home');
Route::get('/berita', function (\Illuminate\Http\Request $request) {
    // If request wants JSON, return API response
    if ($request->wantsJson() || $request->expectsJson() || $request->header('Accept') === 'application/json') {
        $controller = new \App\Http\Controllers\Api\BeritaController();
        return $controller->index();
    }
    // Otherwise, return web view
    return app(\App\Http\Controllers\GuestController::class)->berita();
})->name('berita');
Route::get('/berita/{id}', function (\Illuminate\Http\Request $request, $id) {
    // If request wants JSON, return API response
    if ($request->wantsJson() || $request->expectsJson() || $request->header('Accept') === 'application/json') {
        $controller = new \App\Http\Controllers\Api\BeritaController();
        $berita = \App\Models\Berita::findOrFail($id);
        return $controller->show($berita);
    }
    // Otherwise, return web view
    return app(\App\Http\Controllers\GuestController::class)->beritaShow($id);
})->name('berita.show');
Route::get('/agenda', [GuestController::class, 'agenda'])->name('agenda');
Route::get('/gallery', [GuestController::class, 'gallery'])->name('gallery');
Route::get('/kontak', [GuestController::class, 'kontak'])->name('kontak');
Route::get('/tentang', [GuestController::class, 'tentang'])->name('tentang');

// ======================= Gallery API Routes (Session-based auth for Blade pages) =======================
// These routes MUST be before API routes to handle session-based auth from Blade pages
// Routes use /api prefix but with auth:web middleware instead of auth:sanctum
Route::middleware(['auth:web'])->prefix('api')->group(function () {
    Route::post('/foto/{foto}/like', [\App\Http\Controllers\Api\LikeController::class, 'toggle']);
    Route::get('/foto/{foto}/likes/count', [\App\Http\Controllers\Api\LikeController::class, 'count']);
    Route::get('/foto/{foto}/comments', [\App\Http\Controllers\Api\CommentController::class, 'index']);
    Route::post('/foto/{foto}/comments', [\App\Http\Controllers\Api\CommentController::class, 'store']);
    Route::get('/foto/{foto}/download', [\App\Http\Controllers\Api\DownloadController::class, 'download']);
});

// ======================= API Routes (without /api prefix) =======================
// Public API endpoints for frontend - these check Accept header to differentiate API from web requests
Route::get('/agendas', [AgendaController::class, 'index'])->middleware('api');
Route::get('/agendas/{agenda}', [AgendaController::class, 'show'])->middleware('api');
Route::get('/galeri', function (\Illuminate\Http\Request $request) {
    // If request wants JSON, return API response
    if ($request->wantsJson() || $request->expectsJson() || $request->header('Accept') === 'application/json' || $request->is('api/*')) {
        $controller = new \App\Http\Controllers\Api\GaleriApiController();
        return $controller->index();
    }
    // Otherwise, return web view
    return app(\App\Http\Controllers\GuestController::class)->galeri();
});
Route::get('/galeri/{id}', [GaleriApiController::class, 'show'])->middleware('api');

// ======================= User Auth Routes =======================
Route::prefix('user')->name('user.')->group(function () {
    Route::middleware('guest:web')->group(function () {
        Route::get('/login', [UserAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [UserAuthController::class, 'login']);
        Route::get('/register', [UserAuthController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [UserAuthController::class, 'register']);
    });
    
    Route::middleware('auth:web')->group(function () {
        Route::get('/account', [UserAuthController::class, 'account'])->name('account');
        Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');
    });
});

// ======================= Admin Routes =======================
Route::prefix('admin')->name('admin.')->group(function () {
    // ---------- Auth (Login & Register) ----------
    // Guest middleware ensures only non-authenticated users can access these routes
    Route::middleware('guest:petugas')->group(function () {
        // Login Routes
        Route::get('/login', [AuthController::class, 'showLoginForm'])
            ->name('login');
            
        Route::post('/login', [AuthController::class, 'login'])
            ->name('login.submit');

        // Only register these routes if registration is enabled in config
        if (config('auth.registration_enabled', true)) {
            Route::get('/register', [AuthController::class, 'showRegisterForm'])
                ->name('register');
                
            Route::post('/register', [AuthController::class, 'register'])
                ->name('register.submit');
        }
    });

    // Logout Route (must be outside guest middleware)
    Route::post('/logout', [AuthController::class, 'logout'])
        ->middleware('auth:petugas')
        ->name('logout');

    // Redirect /admin to login if not authenticated (must be before protected routes)
    Route::get('/', function () {
        if (\Illuminate\Support\Facades\Auth::guard('petugas')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('admin.login');
    })->name('home');

    // ---------- Protected Routes (Require Authentication) ----------
    Route::middleware(['auth:petugas', 'admin'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [AuthController::class, 'dashboard'])
            ->name('dashboard');

        // Resource Routes
        Route::resource('galeri', GaleriController::class)
            ->names('galeri');
            
        // Toggle gallery status
        Route::post('/galeri/{galeri}/toggle-status', [GaleriController::class, 'toggleStatus'])
            ->name('galeri.toggle-status');
            
        Route::resource('foto', FotoController::class)
            ->names('foto');
            
        // Toggle photo status
        Route::post('/foto/{foto}/toggle-status', [FotoController::class, 'toggleStatus'])
            ->name('foto.toggle-status');
            
        Route::resource('kategori', KategoriController::class)
            ->names('kategori');
            
        // Toggle category status
        Route::post('/kategori/{kategori}/toggle-status', [KategoriController::class, 'toggleStatus'])
            ->name('kategori.toggle-status');

        // Petugas (Admin Users) Management
        Route::resource('petugas', PetugasController::class)
            ->names('petugas')
            ->middleware('can:manage-users');
    });
});
