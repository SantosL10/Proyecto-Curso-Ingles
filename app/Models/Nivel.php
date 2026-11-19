<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

// Modelo de niveles
#[Fillable(['orden', 'clave', 'tipo', 'nombre'])]
class Nivel extends Model
{
    // Aqui indicamos el nombre real de la base de datos de niveles
    protected $table = 'niveles';
}