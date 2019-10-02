<?php

namespace App;
use DB;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    //
    Protected $table = "cat_mat_materia";
    
    protected $fillable = [
        'codigo_mat','nombre_mar','es_electiva','maximo_cant_preguntas',
    ];

    /**
     * Metodo para obtener las areas pertenecientes a una materia.
     * @author Ricardo
     * @return Array de objetos de area.
     */
    public function areas(){
    	return $this->hasMany('App\Area','id_cat_mat','id_cat_mat');
    }

    public function hayEvaluaciones($materia_id){

        if(!auth()->user()->is_student)
            return 0;

        $fecha_hora_actual = Carbon::now('America/Denver')->format('Y-m-d H:i:s');
        $encuestas = Encuesta::whereDate('fecha_final_encuesta', '>', $fecha_hora_actual)->get();
        $hay_evaluaciones = DB::table('materia_ciclo as mc')
                                ->where('id_cat_mat', $materia_id)
                                ->join('carga_academica as ca','mc.id_mat_ci', '=', 'ca.id_mat_ci')
                                ->join('evaluacion as e', 'e.id_carga', '=', 'ca.id_carg_aca')
                                ->join('turno as t', 't.evaluacion_id', '=', 'e.id')
                                ->where('t.visibilidad', 1)
                                ->whereDate('t.fecha_final_turno', '>', $fecha_hora_actual)
                                ->get();

        if(count($hay_evaluaciones) > 0)
            return true;
        else
            return false;
    }
}
