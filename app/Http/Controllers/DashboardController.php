<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'title' => 'Panel/Dashboard provisional de Edición'
        ]);
    }

}
