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

Route::middleware(['auth', 'activo'])->group(function () {
    // Salir
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Consulta: lista de usuarios.
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    // Guardar usuario nuevo
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    // Edita usuario
    Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');

    // Desactiva usuario
    Route::patch('/usuarios/{usuario}/activo', [UsuarioController::class, 'cambiarEstado'])->name('usuarios.estado');

    // Consulta: lista de niveles
    Route::get('/niveles', [NivelController::class, 'index'])->name('niveles.index');

    Route::get('/front/Proyecto/{ruta}', function (string $ruta) {
        $base = realpath(resource_path('paginas/Proyecto'));
        $archivo = realpath($base . '/' . $ruta);

        if (! $archivo || ! str_starts_with($archivo, $base) || ! is_file($archivo)) {
            abort(404);
        }

        return response()->file($archivo, [
            'Content-Type' => 'text/html',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
        ]);

    })->where('ruta', '.*');
});
