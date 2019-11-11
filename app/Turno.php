<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Evaluacion;
use App\Estudiante;
use App\Clave;
use App\Intento;

class Turno extends Model
{
    protected $table='turno';
    use SoftDeletes;

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

    /**
     * Metodo que obtiene la evaluacion a la que pertenece
     * @author Ricardo Estupinian
     * @return El objeto evaluacion a la que pertenece
     */
    public function evaluacion(){
        return $this->belongsTo('App\Evaluacion');
    }

    /**
     * Metodo para obtener la cantidad de intentos que le faltan al estudiante en un turno.
     * @author Edwin Palacios
     * @return int cantidad de intentos que le faltan
     */
    public function getCantIntentosAttribute(){
        $intento_realizados =0;
        $estudiante = Estudiante::where('user_id', auth()->user()->id)->first();
        $evaluacion = Evaluacion::find($this->evaluacion_id);
        $clave = Clave::where('turno_id',$this->id)->first();
        if(Intento::where('clave_id',$clave->id)
                        ->where('estudiante_id',$estudiante->id_est)
                        ->exists()){
        $intento= Intento::where('clave_id',$clave->id)
                        ->where('estudiante_id',$estudiante->id_est)
                        ->first();
        $intento_realizados = $intento->numero_intento;
        }
        

        return $evaluacion->intentos - $intento_realizados;
    }

    /**
     * verificar si un estudiante tiene permisos especiales de revision en un turno especifico
     * @author Edwin Palacios
     * @return boolean de si el estudiante tiene permiso
     */
    public function getRevisionEstudianteAttribute(){
        $revision_estudiante =0;
        $estudiante = Estudiante::where('user_id', auth()->user()->id)->first();

        $clave = Clave::where('turno_id',$this->id)->first();

        if(Intento::where('clave_id',$clave->id)
                        ->where('estudiante_id',$estudiante->id_est)
                        ->exists()){
        $intento= Intento::where('clave_id',$clave->id)
                        ->where('estudiante_id',$estudiante->id_est)
                        ->first();
        $revision_estudiante = $intento->revision_estudiante;
        }
        return $revision_estudiante;
    }

    /**
     * retorna el id del intento de un estudiante en un turno específico
     * @author Edwin Palacios
     * @return id del intento
     */
    public function getIdIntentoAttribute(){
        $id_intento = 0;
        $estudiante = Estudiante::where('user_id', auth()->user()->id)->first();

        $clave = Clave::where('turno_id',$this->id)->first();

        if(Intento::where('clave_id',$clave->id)
                        ->where('estudiante_id',$estudiante->id_est)
                        ->exists()){
        $intento= Intento::where('clave_id',$clave->id)
                        ->where('estudiante_id',$estudiante->id_est)
                        ->first();
        $id_intento = $intento->id;
        }
        return $id_intento;
    }
}
