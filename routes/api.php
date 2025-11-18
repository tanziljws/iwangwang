<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserAuthController;
use App\Http\Controllers\GaleryController;
use App\Http\Controllers\Api\GaleriApiController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\FotoController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\DownloadController;
use App\Http\Controllers\Api\AgendaController;
use App\Http\Controllers\Api\BeritaController;

// Authentication Routes
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});

// User Auth (for gallery interactions)
Route::prefix('user')->group(function () {
    Route::post('/register', [UserAuthController::class, 'register']);
    Route::post('/login', [UserAuthController::class, 'login']);
    Route::post('/forgot-password', [UserAuthController::class, 'forgotPassword']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [UserAuthController::class, 'me']);
        Route::post('/logout', [UserAuthController::class, 'logout']);
    });
});

// Protected API Routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Your protected routes here
    // Gallery interactions - require login
    // Comments
    Route::get('/foto/{foto}/comments', [CommentController::class, 'index']);
    Route::post('/foto/{foto}/comments', [CommentController::class, 'store']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);

    // Likes
    Route::post('/foto/{foto}/like', [LikeController::class, 'toggle']);
    Route::get('/foto/{foto}/likes/count', [LikeController::class, 'count']);

    // Download photo (and record download)
    Route::get('/foto/{foto}/download', [DownloadController::class, 'download']);

    // List all registered gallery users (for admin dashboard)
    Route::get('/users', [UserAuthController::class, 'index']);
    Route::get('/users/reset-requests', [UserAuthController::class, 'resetRequests']);
    Route::post('/users/{user}/reset-password', [UserAuthController::class, 'resetPassword']);

    // Agenda management
    Route::post('/agendas', [AgendaController::class, 'store']);
    Route::put('/agendas/{agenda}', [AgendaController::class, 'update']);
    Route::delete('/agendas/{agenda}', [AgendaController::class, 'destroy']);

    // Berita management
    Route::post('/berita', [BeritaController::class, 'store']);
    Route::post('/berita/{beritum}', [BeritaController::class, 'update']);
    Route::put('/berita/{beritum}', [BeritaController::class, 'update']);
    Route::delete('/berita/{beritum}', [BeritaController::class, 'destroy']);
});

// Public API Routes

// Galeri API
Route::get('/galeri', [GaleriApiController::class, 'index']);
Route::post('/galeri', [GaleriApiController::class, 'store']);
Route::get('/galeri/{id}', [GaleriApiController::class, 'show']);
Route::put('/galeri/{id}', [GaleriApiController::class, 'update']);
Route::delete('/galeri/{id}', [GaleriApiController::class, 'destroy']);
Route::delete('/galery/{id}', [GaleryController::class, 'destroy']); // Delete galery

// Kategori API (JSON)
Route::get('/kategori', [KategoriController::class, 'indexApi']);
Route::post('/kategori', [KategoriController::class, 'store']);
Route::get('/kategori/{kategori}', [KategoriController::class, 'show']);
Route::put('/kategori/{kategori}', [KategoriController::class, 'update']);
Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy']);

// Foto API
Route::get('/foto', [FotoController::class, 'index']);
Route::post('/foto', [FotoController::class, 'store']);
Route::get('/foto/{id}', [FotoController::class, 'show']);
Route::put('/foto/{id}', [FotoController::class, 'update']);
Route::delete('/foto/{id}', [FotoController::class, 'destroy']);

// Post API
Route::get('/post', [PostController::class, 'index']);
Route::post('/post', [PostController::class, 'store']);
Route::get('/post/{id}', [PostController::class, 'show']);
Route::put('/post/{id}', [PostController::class, 'update']);
Route::delete('/post/{id}', [PostController::class, 'destroy']);

// Agenda public endpoints
Route::get('/agendas', [AgendaController::class, 'index']);
Route::get('/agendas/{agenda}', [AgendaController::class, 'show']);

// Berita public endpoints
Route::get('/berita', [BeritaController::class, 'index']);
Route::get('/berita/{beritum}', [BeritaController::class, 'show']);

// Profile API

Route::get('/profile', [ProfileController::class, 'show']);
Route::put('/profile', [ProfileController::class, 'update']);


// Petugas API
Route::get('/petugas', [PetugasController::class, 'index']);      // GET semua data
Route::post('/petugas', [PetugasController::class, 'store']);     // CREATE data petugas
Route::get('/petugas/{id}', [PetugasController::class, 'show']);  // GET satu data
Route::put('/petugas/{id}', [PetugasController::class, 'update']); // UPDATE data petugas
Route::delete('/petugas/{id}', [PetugasController::class, 'destroy']); // DELETE data petugas


