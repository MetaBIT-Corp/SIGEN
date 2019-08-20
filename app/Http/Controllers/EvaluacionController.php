<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Evaluacion;

class EvaluacionController extends Controller
{
    //

    public function show($id){

    	$evaluacion = Evaluacion::findOrFail($id);

    	return view('evaluacion.detalleEvaluacion')->with(compact('evaluacion'));

    }

    public function getCreate(){

    	return view('evaluacion.createEvaluacion');

    }

    public function postCreate(Request $request){
        dd($request->all());
            
        return back();

    }

    public function listado(){
        $evaluaciones = Evaluacion::all();
    	return view('evaluacion.listadoEvaluacion')->with(compact('evaluaciones'));

    }
}
