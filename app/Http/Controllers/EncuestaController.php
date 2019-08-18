<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Encuesta;
class EncuestaController extends Controller
{
      public function getCreate(){

    	return view('encuesta.createEncuesta');

    }

    public function postCreate(Request $request){
    	dd($request->all());
    	return view('encuesta.createEncuesta');

    }

    public function listado(){
    	$encuestas = Encuesta::all();
    	return view('encuesta.listadoEncuesta')->with(compact('encuestas'));

    }
}
