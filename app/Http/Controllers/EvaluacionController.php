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
}
