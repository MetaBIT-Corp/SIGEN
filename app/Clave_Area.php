<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clave_Area extends Model
{
    protected $table='clave_area';
    
    protected $fillable = [
        'id_area','id_clave','numero_preguntas','aleatorio','peso',
    ];
}
