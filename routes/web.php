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

// Proxy storage files to bypass Apache 403 on symlink
Route::get('/storage/{path}', function ($path) {
    $full = storage_path('app/public/' . $path);
    if (!file_exists($full)) {
        abort(404, 'File not found');
    }
    return response()->file($full);
})->where('path', '.*');

// Alternate media route to fully bypass any web server alias restrictions
Route::get('/media/{path}', function ($path) {
    $full = storage_path('app/public/' . $path);
    if (!file_exists($full)) {
        abort(404, 'File not found');
    }
    return response()->file($full);
})->where('path', '.*');

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ======================= API Routes (without /api prefix) =======================
// Public API endpoints for frontend - must be before guest routes
// These routes check Accept header to differentiate API from web requests
Route::get('/agendas', [AgendaController::class, 'index'])->middleware('api');
Route::get('/agendas/{agenda}', [AgendaController::class, 'show'])->middleware('api');
Route::get('/berita', [BeritaController::class, 'index'])->middleware('api');
Route::get('/berita/{beritum}', [BeritaController::class, 'show'])->middleware('api');
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

// ======================= Guest Routes =======================
Route::get('/', [GuestController::class, 'home'])->name('home');
Route::get('/berita', [GuestController::class, 'berita'])->name('berita');
Route::get('/berita/{id}', [GuestController::class, 'beritaShow'])->name('berita.show');
Route::get('/agenda', [GuestController::class, 'agenda'])->name('agenda');
Route::get('/gallery', [GuestController::class, 'gallery'])->name('gallery');
Route::get('/kontak', [GuestController::class, 'kontak'])->name('kontak');
Route::get('/tentang', [GuestController::class, 'tentang'])->name('tentang');

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
            
        // Redirect /admin to dashboard
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        })->name('home');

    });
});
