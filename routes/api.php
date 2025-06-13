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

// para limpiar
Route::get('/test-log', function () {
    Log::warning('🚨 Ruta de prueba ejecutada');
    return response()->json(['message' => 'Log generado']);
});

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
Route::middleware('auth:api')->post('/notifications/send-fcm', [NotificationController::class, 'sendFCM']);
Route::middleware('auth:api')->post('/save-fcm-token', [FcmTokenController::class, 'saveFcmToken']);

/*Route::middleware('auth:api')->post('/save-fcm-token', function (Request $request) {

   $request->validate([
        'token' => 'required|string',
        'device_type' => 'nullable|string',
        'device_name' => 'nullable|string',
    ]);

    $user = auth()->user();

    FcmToken::updateOrCreate(
        ['user_id' => $user->id, 'token' => $request->token],
        [
            'device_type' => $request->device_type,
            'device_name' => $request->device_name,
            'last_used_at' => now(),
            'is_valid' => true,
        ]
    );

    return response()->json(['status' => 'success']);
});
*/


Route::get('/test-notify/{user}', function (User $user) {
    return app(FcmTokenController::class)->sendNotification($user);
});

