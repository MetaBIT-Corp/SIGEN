<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clave_Area extends Model
{
    protected $table='clave_area';
    
    protected $fillable = [
        'area_id','clave_id','numero_preguntas','aleatorio','peso',
    ];

    /**
     * Metodo para obtener el area que pertenece a X clave, a partir de un objeto Clave_Area.
     * @author Ricardo Estupinian
     * @return Un objeto area que pertenece a determinada clave.
     */
     public function area(){
        return $this->belongsTo('App\Area');
    }

    /**
     *  Metodo para obtener la clave_area_pregunta de un objeto clave_area
     * @author Ricardo Estupinian
     * @return Un array de objetos de clave_area_pregunta.
     */
    public function claves_areas_preguntas(){
    	return $this->hasMany('App\Clave_Area_Pregunta','clave_area_id');
    }
}
