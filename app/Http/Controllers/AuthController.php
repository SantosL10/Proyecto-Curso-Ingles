<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//Controlador de autenticacion
class AuthController extends Controller
{
    //Inicia sesión con correo y contraseña.
    public function login(Request $request)
    {
        // Valida lo que envía el front. Si falta algo, Laravel responde 422.
        $datos = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);
        // Si la contraseña es correcta pero el usuario está desactivado, avisa.
        $usuario = User::where('email', $datos['email'])->first();

        if ($usuario && Hash::check($datos['password'], $usuario->password) && ! $usuario->activo) {
            return response()->json(
                ['mensaje' => 'Tu usuario está desactivado. Contacta al administrador.'],
                403
            );
        }

        // Solo pueden entrar usuarios activos.
        $credenciales = $datos + ['activo' => true];

        if (! Auth::attempt($credenciales)) {
            return response()->json(
                ['mensaje' => 'Correo o contraseña incorrectos.'],
                401
            );
        }

        // Genera una sesion nueva al entrar.
        $request->session()->regenerate();

        // Devuelve solo lo que el front necesita saber del usuario.
        return response()->json(
            $request->user()->only('id', 'name', 'email', 'rol')
        );
    }

    //Cierra la sesion.
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalida la sesion y renueva el token de seguridad.
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['mensaje' => 'Sesión cerrada.']);
    }

    //Devuelve el usuario con sesión iniciada, o null si no hay sesión
    public function sesion(Request $request)
    {
        return response()->json(
            $request->user()?->only('id', 'name', 'email', 'rol')
        );
    }
}