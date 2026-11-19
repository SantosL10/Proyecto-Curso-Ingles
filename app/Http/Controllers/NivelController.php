<?php

namespace App\Http\Controllers;

use App\Models\Nivel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

// Controlador de niveles
class NivelController extends Controller
{
    public function index()
    {
        $niveles = Nivel::orderBy('tipo')->orderBy('orden')->get();
        return response()->json($niveles);
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'tipo'   => 'required|in:interno,externo',
            'clave'  => 'required|string|max:20|unique:niveles,clave',
            'nombre' => ['required', 'string', 'max:50', Rule::unique('niveles')->where('tipo', $request->tipo)],
            'orden'  => ['required', 'integer', 'min:1', 'max:255', Rule::unique('niveles')->where('tipo', $request->tipo)],
        ], $this->mensajes(), $this->atributos());

        $nivel = Nivel::create($datos);

        return response()->json($nivel, 201);
    }

    public function update(Request $request, Nivel $nivel)
    {
        $datos = $request->validate([
            'tipo'   => 'required|in:interno,externo',
            'clave'  => ['required', 'string', 'max:20', Rule::unique('niveles', 'clave')->ignore($nivel->id)],
            'nombre' => ['required', 'string', 'max:50', Rule::unique('niveles')->where('tipo', $request->tipo)->ignore($nivel->id)],
            'orden'  => ['required', 'integer', 'min:1', 'max:255', Rule::unique('niveles')->where('tipo', $request->tipo)->ignore($nivel->id)],
        ], $this->mensajes(), $this->atributos());

        $nivel->update($datos);

        return response()->json($nivel);
    }
}