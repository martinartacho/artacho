<?php

namespace App\View\Components\Dashboard;

use Illuminate\View\Component;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use App\Models\UserSetting;
use Illuminate\Support\Facades\Cache;

class Advanced extends Component
{
    public $currentLanguage;
    public $globalLanguage;
    public $userLanguage;
    public $defaultLanguage;
    public $appLocale;

    public function __construct()
    {
        // 1. Idioma actual en uso
        $this->currentLanguage = App::getLocale();
        
        // 2. Idioma global del sitio (desde la tabla settings)
        $this->globalLanguage = Cache::remember('global_language', now()->addDay(), function () {
            return Setting::where('key', 'language')->value('value') ?? config('app.locale');
        });
        
        // 3. Idioma del usuario (si está autenticado)
        $this->userLanguage = Auth::check() 
            ? UserSetting::where('user_id', Auth::id())
                         ->where('key', 'language')
                         ->value('value')
            : null;
            
        // 4. Idioma por defecto de la aplicación (config/app.php)
        $this->defaultLanguage = config('app.locale');
        
        // 5. Locale de la aplicación (.env)
        $this->appLocale = config('app.locale');
    }
    
    public function render()
    {
        return view('components.dashboard.advanced');
    }
}