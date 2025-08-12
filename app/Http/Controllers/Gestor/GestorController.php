<?php

namespace App\Http\Controllers\Gestor;

use App\Http\Controllers\Controller;

class GestorController extends Controller
{
    public function index()
    {
        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'user'); // Solo muestra usuarios normales
        })->get();

        return view('gestor.users.index', compact('users'));
    }
}
