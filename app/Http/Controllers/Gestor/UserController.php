<?php

namespace App\Http\Controllers\Gestor;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereDoesntHave('roles', function($query) {
            $query->whereIn('name', ['admin', 'gestor']);
        })->latest()->paginate(10);
        
        return view('gestor.users.index', compact('users'));
    }

    // ... métodos limitados para gestores
}