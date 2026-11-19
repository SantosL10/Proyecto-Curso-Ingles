<?php

namespace App\Http\Controllers;

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
}
