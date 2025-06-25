<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FcmToken;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Notification;
use App\Models\Notification_user;
use App\Services\FCMService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class FcmTokenController extends Controller
{

    public function saveFcmToken(Request $request)
    {

	Log::info('✅ Dentro de saveFcmToken');
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

	Log::info('✅ Token FCM recibido y guardado', [
	    'user_id' => auth()->id(),
	    'token' => $request->token,
	    'hora' => now()->toDateTimeString(),
	]);


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
	    Log::warning('en store error 422');
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
	    Log::warning('En store errro del servidor');

            return response()->json([
                'status' => 'error',
                'message' => 'Error del servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function sendNotification(User $user, FCMService $fcmService)
    {
	Log::warning('Dentro de sendNotification, este endpoint ha sido reepplazado');

	return response()->json(['success' => true, 'response' => json_decode($result, true)]);

	return response()->json([
	    'message' => 'Este endpoint ha sido reemplazado por /api/notifications/send-fcm.'
	], 410); // 410 Gone

    	$result = $fcmService->sendToUser($user, 'Bienvenido', 'Has iniciado sesión correctamente.');

	    if (!$result) {
	        return response()->json(['error' => 'Error al enviar la notificación.'], 500);
	    }

	    return response()->json(['success' => true, 'response' => json_decode($result, true)]);
    }


public function getUnreadCount()
{
    $count = Auth::user()->unreadNotifications()->count();
    return response()->json(['count' => $count]);
}


   public function getNotificationsApi()
   {
    Log::warning('Dentro de getNotificationsApi');

   $user = Auth::user();
   $notifications = $user->notifications()
        ->where('notifications.is_published', true)
        ->where('notifications.push_sent', true)
	->where('notification_user.push_sent', true)
        ->select([
            'notifications.id',
            'notifications.title',
            'notifications.content',
            'notifications.is_published',
            'notifications.push_sent',
            'notifications.published_at',
            'notifications.created_at' // Añade esto para evitar ambigüedad
        ])
        ->orderBy('notifications.published_at', 'desc')
        ->orderBy('notifications.created_at', 'desc') // Reemplaza el orden implícito
	->limit(3)
        ->get();

//	Log::warning('Notificaciones filtradas para el usuario: '. $user->id);

    return response()->json(['notifications' => $notifications]);

    }

    // marcar como leida
public function markAsReadApi($notificationId) // Asegúrate de recibir el parámetro
{
    Log::warning('Dentro de markAsReadApi con ID: '.$notificationId);

    try {
        $user = Auth::user();
        
        // Opción 1: Para relación muchos-a-muchos (pivote)
        $affected = $user->notifications()
            ->where('notification_id', $notificationId)
            ->update([
                'read_at' => now(),
                'read' => true
            ]);
	Log::warning("Se actualizó notificación para el usuario: ". $user->id . " notificationId: " .$notificationId  );
        // Opción 2: Si es una relación directa
        // $notification = Notification::findOrFail($notificationId);
        // $notification->read_at = now();
        // $notification->save();
        
        if ($affected === 0) {
            Log::warning("affecte : No se actualizó ninguna notificación para el usuario: ".$user->id." notificationId ".$notificationId);
            return response()->json(['success' => false, 'message' => 'Notificación no encontrada'], 404);
        }
	else
	{
//		Log::warnig("affected es else  ");
	}
        
        Log::warning("Notificación $notificationId marcada como leída para el usuario: ".$user->id. " ". $notificationId );
        return response()->json(['success' => true]);
        
    } catch (\Exception $e) {
        Log::error("Error en markAsReadApi: ".$e->getMessage());
        return response()->json(['success' => false, 'message' => 'Error interno'], 500);
    }
}


}

