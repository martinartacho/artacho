<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FcmTokenController;
use App\Models\User;

// para limpiar
Route::get('/test-log', function () {
    Log::warning('🚨 Ruta de prueba ejecutada');
    return response()->json(['message' => 'Log generado']);
});

// Rutas públicas
Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/register', [AuthController::class, 'register']);

// Rutas protegidas con token JWT
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::put('/change-password', [AuthController::class, 'changePassword']);
    Route::delete('/delete-account', [AuthController::class, 'deleteAccount']);
});

// Rutas notifications fire
Route::middleware('auth:api')->post('/save-fcm-token', [FcmTokenController::class, 'store']);

Route::get('/test-notify/{user}', function (User $user) {
    return app(FcmTokenController::class)->sendNotification($user);
});

