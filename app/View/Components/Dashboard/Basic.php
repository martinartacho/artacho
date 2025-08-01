<?php

namespace App\View\Components\Dashboard;

use Illuminate\View\Component;
use Illuminate\Support\Facades\App;

class Basic extends Component
{
    public $language;

    public function __construct()
    {
        $this->language = App::getLocale();
    }
    
    public function render()
    {
        return view('components.dashboard.basic');
    }
}