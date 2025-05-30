<?php

namespace App\View\Components\Dashboard;

use Illuminate\View\Component;

class Advanced extends Component
{

    
    public $language;

    public function __construct($language = 'en')
    {
        $this->language = $language;
    }
    
    public function render()
    {
        return view('components.dashboard.advanced');
    }
}
