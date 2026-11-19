<?php

namespace App\Http\Controllers;

abstract class Controller
{
    // Mensajes de validación en español
    protected function mensajes(): array
    {
        return [
            'required'    => 'El campo :attribute es obligatorio.',
            'string'      => 'El campo :attribute debe ser texto.',
            'email'       => 'El campo :attribute debe ser un correo válido.',
            'in'          => 'El valor de :attribute no es válido.',
            'integer'     => 'El campo :attribute debe ser un número entero.',
            'unique'      => 'El :attribute ya está registrado.',
            'max.string'  => 'El campo :attribute no puede tener más de :max caracteres.',
            'max.numeric' => 'El campo :attribute no puede ser mayor que :max.',
            'min.string'  => 'El campo :attribute debe tener al menos :min caracteres.',
            'min.numeric' => 'El campo :attribute debe ser al menos :min.',
            'date'            => 'El campo :attribute debe ser una fecha válida.',
            'after_or_equal'  => 'El campo :attribute debe ser igual o posterior a :date.',
        ];
    }

    // Nombres de los campos como se muestran al usuario
    protected function atributos(): array
    {
        return [
            'name'     => 'nombre',
            'email'    => 'correo',
            'password' => 'contraseña',
            'rol'      => 'acceso',
            'clave'    => 'clave',
            'tipo'     => 'tipo',
            'nombre'   => 'nombre',
            'orden'    => 'orden',
            'fecha_inicio'         => 'inicio del periodo',
            'fecha_fin'            => 'fin del periodo',
            'insc_internos_inicio' => 'inicio de inscripción de internos',
            'insc_internos_fin'    => 'fin de inscripción de internos',
            'insc_externos_inicio' => 'inicio de inscripción de externos',
            'insc_externos_fin'    => 'fin de inscripción de externos',
        ];
    }
}