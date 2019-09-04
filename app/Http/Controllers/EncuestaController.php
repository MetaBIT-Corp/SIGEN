<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Encuesta;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EncuestaController extends Controller
{
      public function getCreate(){

    	return view('encuesta.createEncuesta');

    }

    public function postCreate(Request $request){
    	dd($request->all());
    	return view('encuesta.createEncuesta');

    }
    //Función que lista las encuestas creadas de un docente
    //a este listado solo pueden acceder los docentes y el administrador 
    public function listado(){
        if(auth()->user()->IsAdmin){
            $encuestas = Encuesta::all();
        }
        elseif(auth()->user()->IsTeacher){
            $encuestas = Encuesta::all();
        }
    	return view('encuesta.listadoEncuesta')->with(compact('encuestas'));

    }

    public function listado_publico(){

        $encuestas = Encuesta::all();
        return view('encuesta.listadoEncuesta')->with(compact('encuestas'));

    }

    //Funcion rotorna las encuestas de propósito general que se encuentra disponibles en formato JSON  
    public function encuestasDisponibles(){
        $fecha_hora_actual = Carbon::now('America/Denver')->addMinutes(10)->format('Y-m-d H:i:s');
        $encuestas = Encuesta::whereDate('fecha_final_encuesta', '>', $fecha_hora_actual)->get();

        //dd($encuestas);
        $data = ['encuestas'=>$encuestas];
        return $data;
    }
}
