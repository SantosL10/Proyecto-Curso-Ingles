<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

// Controlador de grupos (un horario con docente asignado)
class GrupoController extends Controller
{
    // Todos los horarios, con el docente si ya tienen uno
    public function index()
    {
        $grupos = DB::table('grupos')
            ->join('niveles', 'niveles.id', '=', 'grupos.nivel_id')
            ->join('periodos', 'periodos.id', '=', 'grupos.periodo_id')
            ->leftJoin('users', 'users.id', '=', 'grupos.docente_id')
            ->select(
                'grupos.id',
                'grupos.periodo_id',
                'periodos.nombre as periodo',
                'grupos.nivel_id',
                'niveles.clave',
                'niveles.nombre as nivel',
                'niveles.tipo',
                'grupos.docente_id',
                'users.name as docente',
                'grupos.hora_inicio',
                'grupos.hora_fin'
            )
            ->orderByDesc('periodos.fecha_inicio')
            ->orderBy('niveles.tipo')
            ->orderBy('niveles.orden')
            ->orderBy('grupos.hora_inicio')
            ->get();

        return response()->json($grupos);
    }

    // Docentes activos, para el select del modal
    public function docentes()
    {
        $docentes = User::where('rol', 'docente')
            ->where('activo', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($docentes);
    }

    // Asigna (o quita) el docente de un horario
    public function update(Request $request, Grupo $grupo)
    {
        $datos = $request->validate([
            'docente_id' => [
                'nullable',
                Rule::exists('users', 'id')->where('rol', 'docente')->where('activo', true),
            ],
        ], $this->mensajes(), $this->atributos());

        if (! empty($datos['docente_id'])) {
            $choque = DB::table('grupos')
                ->join('niveles', 'niveles.id', '=', 'grupos.nivel_id')
                ->where('grupos.periodo_id', $grupo->periodo_id)
                ->where('grupos.docente_id', $datos['docente_id'])
                ->where('grupos.id', '<>', $grupo->id)
                ->where('grupos.hora_inicio', '<', $grupo->hora_fin)
                ->where('grupos.hora_fin', '>', $grupo->hora_inicio)
                ->select('niveles.clave', 'grupos.salon', 'grupos.hora_inicio', 'grupos.hora_fin')
                ->first();

            if ($choque) {
                $de = date('g:i A', strtotime($choque->hora_inicio));
                $a = date('g:i A', strtotime($choque->hora_fin));

                return response()->json([
                    'message' => "El docente ya tiene el grupo {$choque->clave} en el salón {$choque->salon} de {$de} a {$a}.",
                ], 422);
            }
        }

        $grupo->update($datos);

        return response()->json($grupo);
    }
}