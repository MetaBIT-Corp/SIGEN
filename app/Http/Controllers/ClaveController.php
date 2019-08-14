<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Clave_Area;
use App\Clave_Area_Pregunta;
use App\Clave;


class ClaveController extends Controller
{

    public function listarClaves($id_turno){
    	$claves = Clave::all();

    	return view('clave.listarClaves')->with(compact('claves'));
    }

    //Funcion para cargar las preguntas de una área mediante AJAX
    public function preguntas_por_area($id){
    	$id_area = Clave_Area::where('id', $id)->first()->area_id;

    	$cap = Clave_Area_Pregunta::where('clave_area_id',$id)->pluck('pregunta_id');
    	
    	$preguntas = DB::table('area')
    					->where('area.id', $id_area)
    					->join('grupo_emparejamiento as grupo', 'area.id', '=', 'grupo.area_id')
    					->join('pregunta as p', 'grupo.id', '=', 'p.grupo_emparejamiento_id')
    					->select('p.id', 'p.pregunta', 'area.titulo')
    					->get();

    	$data = ['p_asignadas'=>$cap, 'preguntas'=>$preguntas];
    	return $data;
    }

    //Función para asignar a la clave las preguntas seleccionadas del área
    public function asignar_preguntas(Request $request){
    	$preguntas = $request->input('preguntas');
    	$id_clave_area = $request->input('clave_area');
    	$mensaje = 'Ninguna pregunta fue seleccionada';
    	$notificacion = 'error';

    	//Almacenando preguntas en la base de datos
    	if($preguntas){
    		DB::table('clave_area_pregunta')->where('clave_area_id', $id_clave_area)->delete();
    		foreach ($preguntas as $pregunta) {
	    		$clave_area_pregunta = new Clave_Area_Pregunta();
	    		$clave_area_pregunta->clave_area_id = $id_clave_area;
	    		$clave_area_pregunta->pregunta_id = $pregunta;

	    		$clave_area_pregunta->save();
    		}
    		$mensaje = 'Preguntas agregadas exitosamente.';
    		$notificacion = 'exito';
    	}
    	

    	return back()->with($notificacion, $mensaje);
    }

}
