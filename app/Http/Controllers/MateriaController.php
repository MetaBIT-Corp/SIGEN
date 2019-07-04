<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Materia;
use App\Docente;
use App\Estudiante;
use DB;

class MateriaController extends Controller
{
    public function listar(){
    	$id=auth()->user()->id;

    	switch (auth()->user()->role) {
    		case 0:
    			$materias=Materia::all();
    			return view("materia.listadoMateria",compact("materias"));
    			break;
    		
    		case 1:
    			$materias=Materia::all();
    			return view("materia.listadoMateria",compact("materias"));
    			break;
    		default:
    			# code...
    			break;
    	}
    }

    public function listarAdmin(){
    	$materias=Materia::all();
    	return view("materia.listadoMateria",compact("materias"));
    }

    public function listarxDocente(){

    	$materias=DB::table('cat_mat_materia')
    	->join('materia_ciclo','cat_mat_materia.id_cat_mat','=','materia_ciclo.id_cat_mat')
    	->join('carga_academica','carga_academica.id_mat_ci','=','materia_ciclo.id_mat_ci')

    	->join('pdg_dcn_docente',function($join){
    		//Consulta Avanzada donde se determina de que docente se trata 
    		$idUser=auth()->user()->id;
    		$join->on('pdg_dcn_docente.id_pdg_dcn','=','carga_academica.id_pdg_dcn')
    		->where('pdg_dcn_docente.user_id','=',$idUser);
    	})
    	->select('cat_mat_materia.*','carga_academica.id_carg_aca')->get();
    	return view("materia.listadoMateria",compact("materias"));
    }

    public function listarxEstudiante(){
    	$materias=DB::table('cat_mat_materia')
    	->join('materia_ciclo','cat_mat_materia.id_cat_mat','=','materia_ciclo.id_cat_mat')
    	->join('carga_academica','carga_academica.id_mat_ci','=','materia_ciclo.id_mat_ci')
    	->join('detalle_insc_est','detalle_insc_est.id_carg_aca','=','carga_academica.id_carg_aca')
    	->join('estudiante',function($join){
    		//Consulta Avanzada donde se determina de que estudinte se trata 
    		$idUser=auth()->user()->id;
    		$join->on('estudiante.id_est','=','detalle_insc_est.id_est')
    		->where('estudiante.user_id','=',$idUser);
    	})
    	->select('cat_mat_materia.*','carga_academica.id_carg_aca')->get();
    	return view("materia.listadoMateria",compact("materias"));
    }
}
