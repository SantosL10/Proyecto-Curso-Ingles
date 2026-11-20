<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                'grupos.salon',
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
            ->orderBy('grupos.salon')
            ->get();

        return response()->json($horarios);
    }

    public function store(Request $request)
    {
        $datos = $this->validar($request);

        if ($respuesta = $this->salonOcupado($datos)) {
            return $respuesta;
        }

        return response()->json(Grupo::create($datos), 201);
    }

    public function update(Request $request, Grupo $horario)
    {
        $datos = $this->validar($request);

        if ($respuesta = $this->salonOcupado($datos, $horario->id)) {
            return $respuesta;
        }
        
        if ($horario->docente_id) {
            $choqueDocente = DB::table('grupos')
                ->where('periodo_id', $horario->periodo_id)
                ->where('docente_id', $horario->docente_id)
                ->where('id', '<>', $horario->id)
                ->where('hora_inicio', '<', $datos['hora_fin'])
                ->where('hora_fin', '>', $datos['hora_inicio'])
                ->exists();

            if ($choqueDocente) {
                return response()->json([
                    'message' => 'El docente asignado a este horario ya tiene otro grupo en esa hora.',
                ], 422);
            }
        }
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

    // Valida los datos; el salón se guarda siempre en mayúsculas y sin espacios
    private function validar(Request $request): array
    {
        $request->merge(['salon' => strtoupper(str_replace(' ', '', (string) $request->salon))]);

        return $request->validate([
            'periodo_id'  => 'required|exists:periodos,id',
            'nivel_id'    => 'required|exists:niveles,id',
            'salon'       => ['required', 'string', 'max:10', 'regex:/^[A-Z0-9]+$/'],
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin'    => 'required|date_format:H:i|after:hora_inicio',
            'cupo'        => 'required|integer|min:1|max:65535',
        ], $this->mensajes(), $this->atributos());
    }

    // Revisa que el salón no esté ocupado en ese periodo y horario
    private function salonOcupado(array $datos, ?int $ignorar = null)
    {
        $choque = DB::table('grupos')
            ->where('periodo_id', $datos['periodo_id'])
            ->where('salon', $datos['salon'])
            ->where('hora_inicio', '<', $datos['hora_fin'])
            ->where('hora_fin', '>', $datos['hora_inicio'])
            ->when($ignorar, fn ($q) => $q->where('id', '<>', $ignorar))
            ->first();

        if ($choque) {
            $de = substr($choque->hora_inicio, 0, 5);
            $a = substr($choque->hora_fin, 0, 5);

            return response()->json([
                'message' => "El salón {$datos['salon']} ya está ocupado de {$de} a {$a} en ese periodo.",
            ], 422);
        }

        return null;
    }
}