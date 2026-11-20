<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HorarioController extends Controller
{
    public function index()
    {
        $horarios = DB::table('grupos')
            ->join('niveles', 'niveles.id', '=', 'grupos.nivel_id')
            ->select(
                'grupos.id',
                'grupos.periodo_id',
                'grupos.nivel_id',
                'niveles.clave',
                'niveles.nombre as nivel',
                'niveles.tipo',
                'grupos.hora_inicio',
                'grupos.hora_fin',
                'grupos.cupo'
            )
            ->selectSub(function ($q) {
                $q->from('inscripciones')
                  ->selectRaw('count(*)')
                  ->whereColumn('inscripciones.grupo_id', 'grupos.id')
                  ->where('inscripciones.estado', '<>', 'rechazada');
            }, 'registrados')
            ->orderBy('niveles.tipo')
            ->orderBy('niveles.orden')
            ->orderBy('grupos.hora_inicio')
            ->get();

        return response()->json($horarios);
    }
    
    public function store(Request $request)
    {
        $datos = $request->validate($this->reglas($request), $this->mensajes(), $this->atributos());

        return response()->json(Grupo::create($datos), 201);
    }

    public function update(Request $request, Grupo $horario)
    {
        $datos = $request->validate($this->reglas($request, $horario->id), $this->mensajes(), $this->atributos());

        $registrados = DB::table('inscripciones')
            ->where('grupo_id', $horario->id)
            ->where('estado', '<>', 'rechazada')
            ->count();

        if ($datos['cupo'] < $registrados) {
            return response()->json([
                'message' => "El cupo no puede ser menor a los {$registrados} alumnos ya registrados.",
            ], 422);
        }

        $horario->update($datos);

        return response()->json($horario);
    }

    private function reglas(Request $request, ?int $ignorar = null): array
    {
        return [
            'periodo_id'  => 'required|exists:periodos,id',
            'nivel_id'    => 'required|exists:niveles,id',
            'hora_inicio' => [
                'required',
                'date_format:H:i',
                Rule::unique('grupos', 'hora_inicio')
                    ->where('periodo_id', $request->periodo_id)
                    ->where('nivel_id', $request->nivel_id)
                    ->ignore($ignorar),
            ],
            'hora_fin'    => 'required|date_format:H:i|after:hora_inicio',
            'cupo'        => 'required|integer|min:1|max:65535',
        ];
    }

}
