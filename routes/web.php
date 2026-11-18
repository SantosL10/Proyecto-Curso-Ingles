<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NivelController;
use App\Http\Controllers\UsuarioController;


Route::get('/', function () {
    return view('welcome');
});

// Autenticacion
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/sesion', [AuthController::class, 'sesion'])->name('sesion');
// Consulta: lista de niveles
Route::get('/niveles', [NivelController::class, 'index'])->name('niveles.index');

Route::middleware('auth')->group(function () {
    // Salir
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Consulta: lista de usuarios.
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
});