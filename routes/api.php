<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\DiaryController;
use App\Http\Controllers\Api\HabitController;
use App\Http\Controllers\Api\StatsController;
use App\Http\Controllers\Api\PomodoroController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// --- Rute Publik (Tanpa Login) ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// --- Rute Terproteksi (Wajib Login/Bawa Token) ---
Route::middleware('auth:sanctum')->group(function () {
    
    // 1. Ambil Data User yang Sedang Login
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // 2. Rute Fitur Utama (Resource API)
    Route::apiResource('tasks', TaskController::class);
    Route::apiResource('diaries', DiaryController::class);
    Route::apiResource('habits', HabitController::class);

    // 3. Rute Baru: Statistik & Analytics
    Route::get('/stats', [StatsController::class, 'index']);

    // 4. Rute Pomodoro: Tambah XP setelah sesi fokus selesai
    Route::post('/pomodoro/complete', [PomodoroController::class, 'complete']);

    // 4. Logout
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::get('/login', function () {
    return response()->json(['message' => 'Unauthenticated'], 401);
})->name('login');