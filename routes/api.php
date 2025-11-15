<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\GaleryController;
use App\Http\Controllers\Api\GaleriApiController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\FotoController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PetugasController;

// Authentication Routes
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});

// Protected API Routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Your protected routes here
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
Route::get('/kategori/{id}', [KategoriController::class, 'show']);
Route::put('/kategori/{id}', [KategoriController::class, 'update']);
Route::delete('/kategori/{id}', [KategoriController::class, 'destroy']);

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

// Profile API

Route::get('/profile', [ProfileController::class, 'show']);
Route::put('/profile', [ProfileController::class, 'update']);


// Petugas API
Route::get('/petugas', [PetugasController::class, 'index']);      // GET semua data
Route::post('/petugas', [PetugasController::class, 'store']);     // CREATE data petugas
Route::get('/petugas/{id}', [PetugasController::class, 'show']);  // GET satu data
Route::put('/petugas/{id}', [PetugasController::class, 'update']); // UPDATE data petugas
Route::delete('/petugas/{id}', [PetugasController::class, 'destroy']); // DELETE data petugas


