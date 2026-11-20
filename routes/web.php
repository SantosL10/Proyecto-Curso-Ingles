<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NivelController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\PeriodoController;

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

    // Guarda niveles
    Route::post('/niveles', [NivelController::class, 'store'])->name('niveles.store');

    // Edita niveles
    Route::put('/niveles/{nivel}', [NivelController::class, 'update'])->name('niveles.update');

    Route::get('/horarios', [HorarioController::class, 'index'])->name('horarios.index');
    Route::post('/horarios', [HorarioController::class, 'store'])->name('horarios.store');
    Route::put('/horarios/{horario}', [HorarioController::class, 'update'])->name('horarios.update');


    Route::get('/periodos', [PeriodoController::class, 'index'])->name('periodos.index');
    Route::post('/periodos', [PeriodoController::class, 'store'])->name('periodos.store');
    Route::put('/periodos/{periodo}', [PeriodoController::class, 'update'])->name('periodos.update');

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
