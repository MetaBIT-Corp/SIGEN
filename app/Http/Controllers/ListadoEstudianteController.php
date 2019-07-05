<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ListadoEstudianteController extends Controller
{
    //
    public function listar($id_mat_ci){
    	$estudiantes=DB::table('estudiante')
    	->join('detalle_insc_est','estudiante.id_est','=','detalle_insc_est.id_est')
    	->join('carga_academica','carga_academica.id_carg_aca','=','detalle_insc_est.id_carg_aca')
    	->join('materia_ciclo','materia_ciclo.id_mat_ci','=','carga_academica.id_mat_ci')
    	->where('materia_ciclo.id_mat_ci','=',$id_mat_ci)
    	->select('estudiante.*')->get();
    	
    	return view("estudiante/listadoEstudiante",compact("estudiantes"));
    }
}
