<?php

namespace App\Providers;
use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Channels\FcmChannel;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         Notification::extend('fcm', function ($app) {
        return new FcmChannel($app->make(\App\Services\FCMService::class));
    });

        \App\Models\User::observe(\App\Observers\UserObserver::class);  
    }
}
