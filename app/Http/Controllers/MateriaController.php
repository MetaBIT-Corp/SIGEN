<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Materia;
use App\Docente;
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

    public function listarDocente(){

    	$materias=DB::table('cat_mat_materia')
    	->join('materia_ciclo','cat_mat_materia.id_cat_mat','=','materia_ciclo.id_cat_mat')
    	->join('carga_academica','carga_academica.id_mat_ci','=','materia_ciclo.id_mat_ci')

    	->join('pdg_dcn_docente',function($join){
    		//Consulta Avanzada donde se determina de que docente se trata 
    		$idUser=auth()->user()->id;
    		$idDoc=Docente::where('user_id',$idUser)->first();
    		$join->on('pdg_dcn_docente.id_pdg_dcn','=','carga_academica.id_pdg_dcn')
    		->where('pdg_dcn_docente.id_pdg_dcn','=',$idDoc->id_pdg_dcn);
    	})
    	->select('cat_mat_materia.*','carga_academica.id_carg_aca')->get();
    	return view("materia.listadoMateria",compact("materias","idDoc"));
    }
}
