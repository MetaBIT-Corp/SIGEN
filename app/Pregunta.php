<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    protected $table='pregunta';
    
    protected $fillable = [
        'id_grupo_emp','pregunta',
    ];
}
