<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clave_Area_Pregunta_Estudiante extends Model
{
	protected $table='clave_area_pregunta_estudiantes';
    
    protected $fillable = [
        'clave_area_id','pregunta_id',
    ];
    
    /**
     * Metodo para obtener un objeto pregunta a partir de clave area pregunta.
     * @author Ricardo Estupinian
     * @return Retorna un objeto pregunta
     */
    public function pregunta(){
    	return $this->belongsTo('App\Pregunta');
    }
}
