<?php

namespace App\View\Components\Dashboard;

use Illuminate\View\Component;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use App\Models\UserSetting;
use Illuminate\Support\Facades\Cache;

class Basic extends Component
{
    public $currentLanguage;
    public $globalLanguage;
    public $userLanguage;
    
    public function __construct()
    {
        $this->currentLanguage = App::getLocale();
        $this->globalLanguage = Cache::remember('global_language', now()->addDay(), function () {
            return Setting::where('key', 'language')->value('value') ?? config('app.locale');
        });
        $this->userLanguage = Auth::check() 
            ? UserSetting::where('user_id', Auth::id())
                         ->where('key', 'language')
                         ->value('value')
            : null;
    }
    
    public function render()
    {
        return view('components.dashboard.basic');
    }
}