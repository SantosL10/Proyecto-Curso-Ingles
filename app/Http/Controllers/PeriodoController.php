<?php

namespace App\Http\Controllers;

use App\Models\Periodo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

// Controlador de periodos
class PeriodoController extends Controller
{
    public function index()
    {
        return response()->json(Periodo::orderByDesc('fecha_inicio')->get());
    }

    public function store(Request $request)
    {
        $datos = $request->validate($this->reglas(), $this->mensajes(), $this->atributos());

        return response()->json(Periodo::create($datos), 201);
    }

    public function update(Request $request, Periodo $periodo)
    {
        $datos = $request->validate($this->reglas($periodo->id), $this->mensajes(), $this->atributos());

        $periodo->update($datos);

        return response()->json($periodo);
    }

    private function reglas(?int $ignorar = null): array
    {
        return [
            'nombre'               => ['required', 'string', 'max:50', Rule::unique('periodos', 'nombre')->ignore($ignorar)],
            'fecha_inicio'         => 'required|date',
            'fecha_fin'            => 'required|date|after_or_equal:fecha_inicio',
            'insc_internos_inicio' => 'required|date',
            'insc_internos_fin'    => 'required|date|after_or_equal:insc_internos_inicio',
            'insc_externos_inicio' => 'required|date',
            'insc_externos_fin'    => 'required|date|after_or_equal:insc_externos_inicio',
        ];
    }
}