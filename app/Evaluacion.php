<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    protected $table='evaluacion';

    protected $fillable = [
        'id_carga','duracion','intentos','nombre_evaluacion','descripcion_evaluacion','preguntas_a_mostrar',
    ];
    
    /**
     * Metodo para obtener los turnos de las evaluaciones.
     * @author Ricardo Estupinian
     * @return type
     */
    public function turnos(){
    	return $this->hasMany('App\Turno');
    }
}
