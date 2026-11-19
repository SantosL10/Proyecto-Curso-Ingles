<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::select('id', 'name', 'email', 'rol', 'activo')
            ->orderBy('name')
            ->get();

        return response()->json($usuarios);
    }
    public function store(Request $request)
    {
        $datos = $request->validate([
            'name'     => 'required|string|max:150',
            'email'    => 'required|email|max:150|unique:users,email',
            'password' => 'required|string|min:8',
            'rol'      => 'required|in:admin,consulta,docente,alumno',
        ]);

        $usuario = User::create($datos);

        return response()->json($usuario, 201);
    }

    public function cambiarEstado(User $usuario)
    {
        if ($usuario->id === auth()->id()) {
            return response()->json(['message' => 'No puedes desactivar tu propio usuario'], 422);
        }

        $usuario->activo = ! $usuario->activo;
        $usuario->save();

        return response()->json($usuario);
    }

    public function update(Request $request, User $usuario)
    {
        $datos = $request->validate([
            'name'     => 'required|string|max:150',
            'email'    => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($usuario->id)],
            'password' => 'nullable|string|min:8',
            'rol'      => 'required|in:admin,consulta,docente,alumno',
        ], $this->mensajes(), $this->atributos());
        
        if (empty($datos['password'])) {
            unset($datos['password']);
        }

        $usuario->update($datos);

        return response()->json($usuario);
    }
}
