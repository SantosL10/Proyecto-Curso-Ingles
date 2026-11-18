<?php

namespace App\Http\Controllers;

use App\Models\User;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::select('id', 'name', 'email', 'rol', 'activo')
            ->orderBy('name')
            ->get();

        return response()->json($usuarios);
    }
}
