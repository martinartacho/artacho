<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FcmToken;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\FCMService;

class FcmTokenController extends Controller
{

    public function saveFcmToken(Request $request)
    {
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
    }


    public function store(Request $request)
    {
        // Validación
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required|string',
            'device_type' => 'required|string|in:web,mobile'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Obtener usuario autenticado
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Guardar token (ajusta según tu modelo)
            $user->fcm_tokens()->updateOrCreate(
                ['device_type' => $request->device_type],
                ['token' => $request->fcm_token]
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Token guardado correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error del servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function sendNotification(User $user, FCMService $fcmService)
    {

	return response()->json([
	    'message' => 'Este endpoint ha sido reemplazado por /api/notifications/send-fcm.'
	], 410); // 410 Gone

    	$result = $fcmService->sendToUser($user, 'Bienvenido', 'Has iniciado sesión correctamente.');

	    if (!$result) {
	        return response()->json(['error' => 'Error al enviar la notificación.'], 500);
	    }

	    return response()->json(['success' => true, 'response' => json_decode($result, true)]);
    }





}
