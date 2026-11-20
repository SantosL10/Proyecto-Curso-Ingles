<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

// Modelo de grupos (cada horario es un grupo)
#[Fillable(['periodo_id', 'nivel_id', 'salon', 'docente_id', 'hora_inicio', 'hora_fin', 'cupo'])]
class Grupo extends Model
{
}