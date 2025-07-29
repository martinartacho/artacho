<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FcmTokenController;
use App\Http\Controllers\NotificationController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use App\Models\FcmToken;
use App\Http\Controllers\Api\FeedbackController;

use App\Models\Notification;
use App\Services\FCMService;



// Rutas públicas
Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink($request->only('email'));

    return $status === Password::RESET_LINK_SENT
        ? response()->json(['message' => __($status)])
        : response()->json(['message' => __($status)], 422);
});


// Ruras feedback
Route::middleware('auth:api')->prefix('feedback')->group(function () {
    Route::post('/', [FeedbackController::class, 'store']);
});

// Rutas protegidas con token JWT
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::put('/change-password', [AuthController::class, 'changePassword']);
    Route::delete('/delete-account', [AuthController::class, 'deleteAccount']);
});


// Rutas notifications Firebase
// ✅ Notificaciones
Route::middleware('auth:api')->group(function () {
    Route::post('/save-fcm-token', [FcmTokenController::class, 'saveFcmToken']);
    Route::get('/unread-count', [FcmTokenController::class, 'unreadCount']);
    Route::get('/notifications-api', [FcmTokenController::class, 'getNotificationsApi']);
    Route::post('/{id}/mark-read-api', [FcmTokenController::class, 'markAsReadApi']);
});

