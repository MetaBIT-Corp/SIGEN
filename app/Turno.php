<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    protected $table='turno';

    protected $fillable = [
        'evaluacion_id','fecha_inicio_turno','fecha_final_turno','visibilidad','contraseña',
    ];

    /**
     * Metodo que obtiene todas las claves de un turno
     * @author Ricardo Estupinian
     * @return Las claves de un turno especifico
     */
    public function claves(){
    	return $this->hasMany('App\Clave');
    }
}
