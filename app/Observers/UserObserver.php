<?php

namespace App\Observers;

use App\Models\User;
use App\Notifications\WelcomeNotification;

class UserObserver
{
    public function created(User $user)
    {
        // Verifica que tenga al menos un token FCM asociado
        // Solo notificar si el usuario tiene al menos un token FCM regis
        if ($user->fcmTokens()->exists()) {
            $user->notify(new WelcomeNotification());
        }
    }
}
