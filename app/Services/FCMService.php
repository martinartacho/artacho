<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;
Use App\Models\FcmToken;
use Google\Auth\OAuth2;

class FCMService
{
        protected string $credentialsPath;

        public function __construct()
	{
        	$this->credentialsPath = storage_path('app/firebase/firebase_credentials.json');
	}


	protected function getAccessToken(): ?string
	{
	    if (!file_exists($this->credentialsPath)) {
	        Log::error("No se encontró el archivo de credenciales FCM: {$this->credentialsPath}");
	        return null;
	    }

	    $jsonKey = json_decode(file_get_contents($this->credentialsPath), true);

	    $oauth = new OAuth2([
        	'audience' => 'https://oauth2.googleapis.com/token',
	        'issuer' => $jsonKey['client_email'],
        	'signingAlgorithm' => 'RS256',
	        'signingKey' => $jsonKey['private_key'],
        	'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
	        'tokenCredentialUri' => $jsonKey['token_uri'],
	    ]);

	    $token = $oauth->fetchAuthToken();

	    return $token['access_token'] ?? null;
	}



	public function sendToUser(User $user, string $title, string $body): array|bool
	{
	    $tokens = FcmToken::where('user_id', $user->id)->pluck('token');

	    if ($tokens->isEmpty()) {
        	Log::warning("El usuario {$user->id} no tiene tokens FCM.");
	        return false;
	    }

	    $accessToken = $this->getAccessToken();
	    if (!$accessToken) {
        	return false;
	    }

	    $credentials = json_decode(file_get_contents($this->credentialsPath), true);
	    $projectId = $credentials['project_id'];

	    $successCount = 0;
	    $responses = [];

	    foreach ($tokens as $token) {
        	$message = [
	            'message' => [
        	        'token' => $token,
                	'notification' => [
	                    'title' => $title,
        	            'body' => $body,
                	],
	            ],
        	];

	        $response = Http::withToken($accessToken)
        	    ->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", $message);

	        if ($response->successful()) {
        	    $successCount++;
	            $responses[] = ['token' => $token, 'status' => 'success'];
        	} else {
	            Log::error("❌ Error al enviar notificación FCM al token {$token}", [
        	        'response' => $response->json()
	            ]);
        	    $responses[] = ['token' => $token, 'status' => 'failed', 'error' => $response->json()];
	        }
	    }

	    return [
        	'sent' => $successCount,
	        'total' => $tokens->count(),
        	'results' => $responses
	    ];
	}

}
