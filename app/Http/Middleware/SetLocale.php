<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;


class SetLocale
{
    public function handle($request, Closure $next)
    {
        // Prioridad 1: Idioma de la sesión
        if (session('locale')) {
            App::setLocale(session('locale'));
            return $next($request);
        }
        
        // Prioridad 2: Idioma del usuario (si está autenticado)
        if (Auth::check()) {
            $userLang = Auth::user()->settings()->where('key', 'language')->value('value');
            
            if ($userLang) {
                App::setLocale($userLang);
                session()->put('locale', $userLang);
                return $next($request);
            }
        }
        
        // Prioridad 3: Idioma global del sitio
        $lang = Cache::remember('global_language', now()->addDay(), function () {
            return Setting::where('key', 'language')->value('value') ?? config('app.locale');
        });

        App::setLocale($lang);
        session()->put('locale', $lang);

        return $next($request);
    }
   
    /*public function handle($request, Closure $next)
    {
        // Prioridad 1: Idioma del usuario (si está autenticado)
        if (Auth::check()) {
            $userLang = Auth::user()->getSetting('language');
            
            if ($userLang) {
                App::setLocale($userLang);
                return $next($request);
            }
        }
        
        // Prioridad 2: Idioma global del sitio
        $lang = Cache::remember('global_language', now()->addDay(), function () {
            return Setting::where('key', 'language')->value('value') ?? config('app.locale');
        });

        App::setLocale($lang);

        return $next($request);        
    }*/
}
