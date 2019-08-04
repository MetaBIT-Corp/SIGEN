<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clave_Area_Pregunta extends Model
{
    protected $table='clave_area_pregunta';
    
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
