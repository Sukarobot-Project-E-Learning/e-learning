<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public Auth Routes (no token required)
Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/refresh', [AuthController::class, 'refreshToken'])->name('refresh');
});

// Protected Routes (JWT token required)
Route::middleware(['jwt.auth'])->prefix('v1')->name('api.')->group(function () {
    // User info
    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    })->name('user');
    
    // Profile routes
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
