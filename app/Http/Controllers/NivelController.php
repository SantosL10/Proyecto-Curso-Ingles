<?php

namespace App\Http\Controllers;

use App\Models\Nivel;

// Controlador de niveles
class NivelController extends Controller
{
    public function index()
    {
        // Pide al modelo todos los niveles
        $niveles = Nivel::orderBy('tipo')->orderBy('orden')->get();
        return response()->json($niveles);
    }
}
