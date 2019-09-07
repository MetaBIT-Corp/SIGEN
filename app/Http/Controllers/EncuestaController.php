<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Encuesta;
use App\Docente;
use Carbon\Carbon;
use App\Intento;
use App\Clave;
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
            $docente= Docente::where('user_id',auth()->user()->id)->first();
            if($docente){
                $encuestas = Encuesta::where('id_docente',$docente->id_pdg_dcn)->get();
            }else{
                $encuestas=array();
            }
        }
    	return view('encuesta.listadoEncuesta')->with(compact('encuestas'));
    }

    public function listado_publico(){
        $encuestas = Encuesta::all();
        return view('encuesta.Encuestas')->with(compact('encuestas'));

    }

    public function eliminarEncuesta(Request $request){
        $id_encuesta = $request->input('id_encuesta');

        if($id_encuesta){
            $intento = Intento::where('encuesta_id', $id_encuesta)->get();
            $clave = Clave::where('encuesta_id', $id_encuesta)->get();
            $encuesta = Encuesta::find($id_encuesta);

            $notificaicon = 'exito';
            $mensaje = 'La encuesta fue eliminada con éxito';

            if(count($intento) || count($clave)){
                $notificaicon = 'error';
                $mensaje = 'Esta encuesta no se puede eliminar porque ya fue asignada';                

            }
            else{
                $encuesta->delete();
            }   
        }

        return back()->with($notificaicon, $mensaje);
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
