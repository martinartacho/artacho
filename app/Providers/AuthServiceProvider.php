<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Models\Notification;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        
        // Gates para notificaciones
        Gate::define('view-notification', function (User $user, Notification $notification) {
            return $user->hasRole(['admin', 'gestor']) || 
                ($user->hasRole('editor') && $notification->sender_id == $user->id) ||
                $notification->recipients->contains($user->id);
        });
        
        Gate::define('edit-notification', function (User $user, Notification $notification) {
            return $user->hasRole(['admin', 'gestor']) || 
                ($user->hasRole('editor') && $notification->sender_id == $user->id);
        });

        Gate::define('update-notification', function (User $user, Notification $notification) {
            return $user->hasRole(['admin', 'gestor']) || 
                ($user->hasRole('editor') && $notification->sender_id == $user->id);
        });

        Gate::define('delete-notification', function (User $user, Notification $notification) {
            return $user->hasRole(['admin', 'gestor']) || 
                ($user->hasRole('editor') && $notification->sender_id == $user->id);
        });

        // Gates para acciones generales (sin necesidad de modelo)
        Gate::define('list-notifications', function (User $user) {
            return $user->hasAnyRole(['admin', 'gestor', 'editor', 'user']);
        });

        Gate::define('create-notification', function (User $user) {
            return $user->hasAnyRole(['admin', 'gestor', 'editor']);
        });

        Gate::define('publish-notification', function (User $user) {
            return $user->hasAnyRole(['admin', 'gestor']);
        });

    }
}