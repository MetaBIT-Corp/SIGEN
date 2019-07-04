<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ListadoEstudianteController extends Controller
{
    //
    public function listar($carga_academica){
    	$estudiantes=DB::table('estudiante')
    	->join('detalle_insc_est','estudiante.id_est','=','detalle_insc_est.id_est')
    	->join('carga_academica','carga_academica.id_carg_aca','=','detalle_insc_est.id_carg_aca')
    	->where('carga_academica.id_carg_aca','=',$carga_academica)
    	->select('estudiante.*')->get();
    	return view("estudiante/listadoEstudiante",compact("estudiantes"));
    }
}
