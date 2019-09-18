<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clave extends Model
{
    protected $table='clave';
    
    protected $fillable = [
        'turno_id','numero_clave',
    ];

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
    
    /**
     * Metodo para obtener todas los registros de la relacion entre area y clave.
     * @author Ricardo Estupinian
     * @return Retorna un array de objetos Clave_Area
     */
    public function clave_areas(){
        return $this->hasMAny('App\Clave_Area');
    }

    public function intentos(){
        return $this->hasMAny('App\Intento');
    }
}
