<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'title' => 'Panel/Dashboard provisional de Edición'
        ]);
    }

}
