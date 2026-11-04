<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CepController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

Route::get('/cep/{cep}', [CepController::class, 'show']);

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::prefix('profile')->group(function () {
    Route::put('/', [ProfileController::class, 'updateProfile']);
    Route::put('/password', [ProfileController::class, 'updatePassword']);
    Route::delete('/account', [ProfileController::class, 'deleteAccount']);
});
