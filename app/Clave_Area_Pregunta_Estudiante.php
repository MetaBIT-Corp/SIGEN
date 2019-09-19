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
    
    //Relaciones con otras tablas
    public function pregunta(){
    	return $this->belongsTo('App\Pregunta');
    }

    public function clave_area(){
        return $this->belongsTo(Clave_Area::class);
    }
}
