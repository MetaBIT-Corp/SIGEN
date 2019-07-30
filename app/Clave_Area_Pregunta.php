<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clave_Area_Pregunta extends Model
{
    protected $table='clave_area_pregunta';
    
    protected $fillable = [
        'id_clave_area','id_pregunta',
    ];
}
