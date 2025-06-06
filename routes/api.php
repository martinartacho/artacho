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
Route::middleware('auth:api')->post('/save-fcm-token', function (Request $request) {

    Log::info("✅ Estamos dentro en routes save-fcm-token ");
    $user = $request->user();
    $token = $request->input('token');

    if (!$token) {
        return response()->json(['error' => 'Token FCM requerido'], 400);
    }

    // Guardar en base de datos o log (solo para prueba inicial)
    \Log::info("📲 FCM token recibido de {$user->email}: $token");
 // Evitar duplicados (opcional pero recomendado)
    $existing = FcmToken::where('user_id', $user->id)->where('token', $token)->first();
    if (!$existing) {
        FcmToken::create([
            'user_id' => $user->id,
            'token' => $token,
        ]);
        Log::info("📲 Token FCM guardado en la tabla fcm_tokens para {$user->email}");
    } else {
        Log::info("🔁 Token FCM ya existe para {$user->email}");
    }

    return response()->json(['message' => 'Token FCM procesado']);

});



Route::get('/test-notify/{user}', function (User $user) {
    return app(FcmTokenController::class)->sendNotification($user);
});

