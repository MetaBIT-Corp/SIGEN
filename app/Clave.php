<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clave extends Model
{
    protected $table='clave';

    /**
     * Metodo que retorna al turno que pertenece la clave
     * @author Ricardo Estupinian
     * @return Retorna el turno si no tiene retorna null
     */
    public function turno(){
    	return $this->belongsTo('App\Turno');
    }

    /**
     * Metodo que retorna la encuesta a la que pertenece la clave
     * @author Ricardo Estupinian
     * @return Retorna la encuesta si no tiene retorna null
     */
    public function encuesta(){
    	return $this->belongsTo('App\Encuesta');
    }
}
