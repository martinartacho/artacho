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



// Mantén estas rutas solo para compatibilidad temporal
/*
Route::middleware('auth:api')->group(function () {
    Route::get('/unread-count-api', [FcmTokenController::class, 'getUnreadCountApi']);
    Route::get('/notifications-api', [FcmTokenController::class, 'getNotificationsApi']);
    Route::post('/{id}/mark-read-api', [FcmTokenController::class, 'markAsReadApi']);
});
*/




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

// Rutas protegidas con token JWT
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::put('/change-password', [AuthController::class, 'changePassword']);
    Route::delete('/delete-account', [AuthController::class, 'deleteAccount']);
});


// Rutas notifications Firebase
Route::middleware('auth:api')->prefix('notifications')->group(function () {
    Route::get('/', [NotificationController::class, 'index']);
    Route::get('/unread-count', [NotificationController::class, 'unreadCount']);
     Route::post('/{id}/mark-read', [NotificationController::class, 'markAsRead']);
    Route::post('/send-fcm', [NotificationController::class, 'sendFCM']);
    Route::post('/save-fcm-token', [FcmTokenController::class, 'saveFcmToken']);

    Route::get('/unread-count', [FcmTokenController::class, 'unreadCount']);


});
// Route::middleware('auth:api')->get('/unread-count-api', [FcmTokenController::class, 'getUnreadCountApi']);
// Route::middleware('auth:api')->get('/notifications-api', [FcmTokenController::class, 'getNotificationsApi']);
// Route::middleware('auth:api')->post('/{id}/mark-read-api', [FcmTokenController::class, 'markAsReadApi']);



Route::get('/test-notify/{user}', function (User $user) {
    return app(FcmTokenController::class)->sendNotification($user);
});

