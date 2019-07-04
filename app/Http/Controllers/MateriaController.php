<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Materia;

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
}
