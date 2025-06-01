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
    $token = $user->fcm_token;

    if (!$token) {
        return response()->json(['error' => 'Este usuario no tiene un token FCM registrado.'], 400);
    }

    $credentialsPath = storage_path('app/firebase/firebase_credentials.json');
    $credentials = json_decode(file_get_contents($credentialsPath), true);

    $auth = new \Google\Auth\OAuth2([
        'audience' => 'https://oauth2.googleapis.com/token',
        'issuer' => $credentials['client_email'],
        'signingAlgorithm' => 'RS256',
        'signingKey' => $credentials['private_key'],
        'tokenCredentialUri' => 'https://oauth2.googleapis.com/token',
        'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
    ]);

    $auth->fetchAuthToken();
    $accessToken = $auth->getLastReceivedToken()['access_token'];

    $projectId = $credentials['project_id'];

    $data = [
        "message" => [
            "token" => $token,
            "notification" => [
                "title" => "Notificación desde Laravel",
                "body" => "Hola {$user->name}, este es un mensaje de prueba.",
            ],
            // Puedes agregar "data" opcional si deseas incluir payload personalizado
        ]
    ];

    $client = new \GuzzleHttp\Client();
    $response = $client->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", [
        'headers' => [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ],
        'json' => $data,
    ]);

    return response()->json([
        'status' => 'Notificación enviada',
        'firebase_response' => json_decode($response->getBody(), true)
    ]);
}


}
