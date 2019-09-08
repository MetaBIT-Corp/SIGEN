<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clave_Area extends Model
{
    protected $table='clave_area';
    
    protected $fillable = [
        'area_id','clave_id','numero_preguntas','aleatorio','peso',
    ];

    //Relaciones

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

    //Obtiene la cantidad de preguntas de la clave_area
    public function clave_area_preguntas_estudiante(){
        return $this->hasMany(Clave_Area_Pregunta_Estudiante::class, 'clave_area_id', 'id');
    }


    //Accessors

    //El atributo calcula mostrará el mensaje en base al campo aleatorio
    public function getEsAleatorioAttribute(){
        if($this->aleatorio==1)
            return 'Sí';
        else
            return 'No';
    }

    /*El campo calculado mostrará la cantidad de preguntas agregadas si es modalidad manual en caso 
    contrario mostrará la cantidad especificada en el objeto*/
    public function getCantidadPreguntasAttribute(){
        if($this->aleatorio)
            return $this->numero_preguntas;
        else
            return count($this->claves_areas_preguntas);
    }
}
