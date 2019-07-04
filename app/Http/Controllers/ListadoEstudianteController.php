<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ListadoEstudianteController extends Controller
{
    //
    public function listar(){
    	$informacion= ["hola"];
    	return view("estudiante/listadoEstudiante",compact("informacion"));
    }
}
