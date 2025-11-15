<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\FotoController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PetugasController;

// Proxy storage files to bypass Apache 403 on symlink
Route::get('/storage/{path}', function ($path) {
    $full = storage_path('app/public/' . $path);
    abort_unless(file_exists($full), 404);
    return response()->file($full);
})->where('path', '.*');

// Alternate media route to fully bypass any web server alias restrictions
Route::get('/media/{path}', function ($path) {
    $full = storage_path('app/public/' . $path);
    abort_unless(file_exists($full), 404);
    return response()->file($full);
})->where('path', '.*');

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ======================= Guest Routes =======================
Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::get('/tentang', [GuestController::class, 'tentang'])->name('tentang');
Route::get('/galeri', [GuestController::class, 'galeri'])->name('galeri');
Route::get('/kontak', [GuestController::class, 'kontak'])->name('kontak');

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
