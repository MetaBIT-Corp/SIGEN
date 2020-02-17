<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Turno;
use DB;

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
    public function carga_academica(){ 
        return $this->belongsTo(CargaAcademica::class, 'id_carga', 'id_carg_aca');
    }

     /**
     * Metodo para obtener la cantidad de intentos que le faltan al estudiante en una evaluacion.
     * @author Edwin Palacios
     * @return int cantidad de intentos que le faltan
     */
    public function getCantIntentosAttribute(){
        $intento_realizados =0;
        $estudiante = Estudiante::where('user_id', auth()->user()->id)->first();
        //$turnos = $this->hasMany('App\Turno');
        $turnos = Turno::where('evaluacion_id', $this->id)->get();
        foreach ($turnos as $turno) {
                $claves = $turno->claves;
                foreach ($claves as $clave) {

                $intento_realizados += Intento::where('clave_id',$clave->id)
                                ->where('estudiante_id',$estudiante->id_est)
                                ->count();
                }
            }
        return $this->intentos - $intento_realizados;
    }
}
