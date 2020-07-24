<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class CicloMateria extends Model
{
    //
    Protected $table = "materia_ciclo";
    
    protected $fillable = [
        'id_cat_mat','id_ciclo',
    ];
    public function cargas(){
    	return $this->hasMany(CargaAcademica::class, 'id_mat_ci', 'id_mat_ci');
    }

    
    public function estudiantesIncritos($id_mat_ci){
        $value = false;
        $estudiantes=DB::table('materia_ciclo')
        ->join('carga_academica','materia_ciclo.id_mat_ci','=','carga_academica.id_mat_ci')
        ->join('detalle_insc_est','carga_academica.id_carg_aca','=','detalle_insc_est.id_carg_aca')
        ->where('materia_ciclo.id_mat_ci',$id_mat_ci)
        ->first();

        if(isset($estudiantes)){
            $value = true;
        }
        return $value;
    }
}
