<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

// Modelo de periodos
#[Fillable([
    'nombre',
    'fecha_inicio',
    'fecha_fin',
    'insc_internos_inicio',
    'insc_internos_fin',
    'insc_externos_inicio',
    'insc_externos_fin',
])]
class Periodo extends Model
{
}