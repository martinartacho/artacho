<?php

namespace App\View\Components\Dashboard;

use Illuminate\View\Component;
use Illuminate\Support\Facades\App;

class Advanced extends Component
{

    
    public $language;

    public function __construct($language = 'en')
    {
        //$this->language = $language;
        $this->language = App::getLocale();
    }
    
    public function render()
    {
        return view('components.dashboard.advanced');
    }
}
