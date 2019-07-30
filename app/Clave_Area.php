<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clave_Area extends Model
{
    protected $table='clave_area';
    
    protected $fillable = [
        'area_id','clave_id','numero_preguntas','aleatorio','peso',
    ];
}
