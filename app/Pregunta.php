<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    protected $table='pregunta';
    protected $fillable = [
        'grupo_emparejamiento_id','pregunta',
    ];

    /**
     *  Metodo que obtiene todas la opcion/es de una pregunta.
     * @author Ricardo Estupinian
     * @return Un array de opciones de la pregunta especificada.
     */
    public function opciones(){
    	return $this->hasMany('App\Opcion');
    }
}
