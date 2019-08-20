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

    /**
     * Metodo para obtener las preguntas que pertenecen a un grupo emparejamiento
     * @author Ricardo Estupinian
     * @return Array de Objetos pregunta
    **/
    public function area(){
    	return $this->belongsTo('App\Area');
    }

}
