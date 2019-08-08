<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grupo_Emparejamiento extends Model
{
    protected $table='grupo_emparejamiento';
    
    protected $fillable = [
        'area_id','descripcion_grupo_emp',
    ];

    public function preguntas(){
    	return $this->hasMany(Pregunta::class, 'id', 'grupo_emparejamiento_id');
    }

}
