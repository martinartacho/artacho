<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SetLocale
{
   public function handle($request, Closure $next)
    {
        // Obtener idioma desde configuración global con caché
        $lang = Cache::remember('global_language', now()->addDay(), function () {
            return Setting::where('key', 'language')->value('value') ?? config('app.locale');
        });

        App::setLocale($lang);

        return $next($request);
        /*
        // Obtener idioma desde configuración global
        $lang = cache()->rememberForever('global_language', function () {
            return Setting::where('key', 'language')->value('value') ?? config('app.locale');
        });

        App::setLocale($lang);

        return $next($request);*/
    }
}
