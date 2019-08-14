<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Clave_Area;
use App\Clave_Area_Pregunta;
use App\Clave;


class ClaveController extends Controller
{

    //Funcion para listar las claves asignadas a un turno
    public function listarClaves($id_turno){
    	$claves = Clave::where('id', $id_turno)->get();

    	return view('clave.listarClaves')->with(compact('claves'));
    }

    //Funcion para cargar las preguntas de una área mediante AJAX
    public function preguntasPorArea($id){
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

    //Funcion para cargar las preguntas asignadas a una clave
    public function preguntasAgregadas($id){

        $preguntas_asginadas = DB::table('clave_area_pregunta as cap')
                                    ->where('cap.clave_area_id', $id)
                                    ->join('pregunta as p', 'p.id', '=', 'cap.pregunta_id')
                                    ->select('p.pregunta')
                                    ->get();

        return $preguntas_asginadas;
    }

    //Función para asignar a la clave las preguntas seleccionadas del área
    public function asignarPreguntas(Request $request){
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

    //Funcion para editar datos de la área asignada a la clave
    public function editarClaveArea(Request $request){
        $id_clave_area = $request->input('id_clave_area');
        $clave_area = Clave_Area::find($id_clave_area);

        $rules = [
            'numero_preguntas' => 'required|numeric|min:1|max:20',
            'peso' => 'min:0|max:100|numeric|required'
        ];

        $messages = [
            'numero_preguntas.min' => 'Debe tomar al menos una pregunta del área',
            'numero_preguntas.max' => 'No se puede asignar mas de 20 preguntas de una área a la clave',
            'numero_preguntas.required' => 'Debe ingresar el número de preguntas a tomar del área',
            'peso.min' => 'El peso del área no puede ser negativo',
            'peso.max' => 'No se puede asignar un peso mayor a 100%',
            'peso.required' => 'Debe ingresar el peso que tendrá área'
        ];

        $this->validate($request, $rules, $messages);

        $clave_area->numero_preguntas = $request->input('numero_preguntas');
        $clave_area->peso = $request->input('peso');

        $clave_area->save();

        return back()->with('exito', 'Los datos fueron modificafos con éxito');

    }

}
