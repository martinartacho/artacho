<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FcmToken;
use Illuminate\Http\Request;
use App\Models\User;

class FcmTokenController extends Controller
{
    public function store(Request $request)
    {
     
         
        $request->validate([
            'token' => 'required|string|max:255',
        ]);

        $user = $request->user();

        // Guarda o actualiza el token
        FcmToken::updateOrCreate(
            ['user_id' => $user->id],
            ['token' => $request->token]
        );

        return response()->json(['message' => 'Token FCM guardado correctamente.']);
    }

    public function sendNotification(User $user)
    {
        $fcmToken = $user->fcm_token;

        $data = [
            "to" => $fcmToken,
            "notification" => [
                "title" => "Bienvenido",
                "body" => "Has iniciado sesión correctamente.",
            ],
            "data" => [
                "customKey" => "customValue",
            ]
        ];

        $client = new \GuzzleHttp\Client();
        $response = $client->post("https://fcm.googleapis.com/fcm/send", [
            'headers' => [
               'Authorization' => 'key=' . env('FCM_SERVER_KEY'),
               'Content-Type' => 'application/json',
            ],
            'json' => $data,
        ]);

        return $response->getBody()->getContents();
    }
}
