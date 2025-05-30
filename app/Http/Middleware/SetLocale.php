<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use App\Models\Setting;

class SetLocale
{
   public function handle($request, Closure $next)
    {
        // Obtener idioma desde configuración global
        $lang = cache()->rememberForever('global_language', function () {
            return Setting::where('key', 'language')->value('value') ?? config('app.locale');
        });

        App::setLocale($lang);

        return $next($request);
    }
}
