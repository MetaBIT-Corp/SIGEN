<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    //
    Protected $table = "cat_mat_materia";
    
    protected $fillable = [
        'codigo_mat','nombre_mar','es_electiva','maximo_cant_preguntas',
    ];
}
