<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    protected $table='turno';
    
    protected $fillable = [
        'id_evaluacion','fecha_inicio_turno','fecha_final_turno','visibilidad','contraseña',
    ];
}
