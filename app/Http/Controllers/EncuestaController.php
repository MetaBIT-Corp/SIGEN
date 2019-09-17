<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Encuesta;
use App\Docente;
use Carbon\Carbon;
use App\Intento;
use App\Clave;
use Illuminate\Support\Facades\DB;
use DateTime;

class EncuestaController extends Controller
{
    public function getCreate(){
    	return view('encuesta.createEncuesta');
    }

    public function postCreate(Request $request){
    	//dd($request->all());
        
        $rules =[
            
            'title' => ['required', 'string','min:5','max:191'],
            'description' => ['required'],
            'fecha_inicio' => ['required', 'date', 
                function ($attribute, $value, $fail) {
                    $fecha_actual = Carbon::now('America/Denver')->format('m/d/Y g:i A');
                    if (($value < $fecha_actual)) {
                        $fail($fecha_actual.'La fecha inicial debe ser mayor a la actual.' );
                    }
                },
            ],
            'fecha_final' => ['required' , 'date', 'after:fecha_inicio'],
        ];
        /* Mensaje de Reglas de Validación */
        $messages = [
            
            'title.required' => 'Debe de ingresar un título para la encuesta',
            'title.min' => 'El título debe contener como mínimo 5 caracteres',
            'description.required' => 'Debe de ingresar una descripción para la encuesta',
            'fecha_inicio.required' => 'Debe de indicar la fecha de inicio del periodo de disponibilidad',
            'fecha_final.required' => 'Debe de indicar la fecha de Fin del periodo de disponibilidad',
            'fecha_final.after' => 'La fecha final debe ser mayor a la fecha inicial ',
        ];
        
        $this->validate($request,$rules,$messages);
        $docente= Docente::where('user_id',auth()->user()->id)->first();
        $encuesta = new Encuesta();
        $encuesta->titulo_encuesta= $request->input('title');
        $encuesta->id_docente= $docente->id_pdg_dcn;
        $encuesta->fecha_inicio_encuesta= DateTime::createFromFormat(
            'm/d/Y H:i A', 
            $request->input('fecha_inicio'))->format('Y-m-d H:i:s');
        $encuesta->fecha_final_encuesta=DateTime::createFromFormat(
            'm/d/Y H:i A', 
            $request->input('fecha_final'))->format('Y-m-d H:i:s');
        $encuesta->descripcion_encuesta=$request->input('description');
        $encuesta->visible=0;

        
            

        if(isset($request->all()['visible']))
            $encuesta->visible = 1;
        $encuesta->save();
        //return back()->with('notification','Se registró exitosamente');
        return redirect()->action('EncuestaController@listado');

    }

    public function getUpdate($id){
        $encuesta = Encuesta::find($id);
        $encuesta->fecha_inicio_encuesta= DateTime::createFromFormat(
            'Y-m-d H:i:s',$encuesta->fecha_inicio_encuesta)->format('m/d/Y g:i A');
        $encuesta->fecha_final_encuesta=DateTime::createFromFormat(
            'Y-m-d H:i:s',$encuesta->fecha_final_encuesta)->format('m/d/Y g:i A');
        $fecha_actual = Carbon::now('America/Denver')->format('m/d/Y g:i A');

        $se_puede_editar=true;
        if($encuesta->fecha_inicio_encuesta<=$fecha_actual){
            $se_puede_editar=false;
        }
        return view('encuesta.updateEncuesta')->with(compact('encuesta','se_puede_editar'));
    }

    public function postUpdate($id, Request $request){
        //dd($request->all());
        $rules =[
            
            'title' => ['required', 'string','min:5','max:191'],
            'description' => ['required'],
            'fecha_inicio' => ['required', 'date', 
                function ($attribute, $value, $fail) {
                    $fecha_actual = Carbon::now('America/Denver')->format('m/d/Y g:i A');
                    if (($value < $fecha_actual)) {
                        $fail($fecha_actual.'La fecha inicial debe ser mayor a la actual.' );
                    }
                },
            ],
            'fecha_final' => ['required' , 'date', 'after:fecha_inicio'],
        ];
        /* Mensaje de Reglas de Validación */
        $messages = [
            
            'title.required' => 'Debe de ingresar un título para la encuesta',
            'title.min' => 'El título debe contener como mínimo 5 caracteres',
            'description.required' => 'Debe de ingresar una descripción para la encuesta',
            'fecha_inicio.required' => 'Debe de indicar la fecha de inicio del periodo de disponibilidad',
            'fecha_final.required' => 'Debe de indicar la fecha de Fin del periodo de disponibilidad',
            'fecha_final.after' => 'La fecha final debe ser mayor a la fecha inicial ',
        ];
        
        $this->validate($request,$rules,$messages);
        $docente= Docente::where('user_id',auth()->user()->id)->first();
        $encuesta = Encuesta::find($id);
        $encuesta->titulo_encuesta= $request->input('title');
        $encuesta->fecha_inicio_encuesta= DateTime::createFromFormat(
            'm/d/Y H:i A', 
            $request->input('fecha_inicio'))->format('Y-m-d H:i:s');
        $encuesta->fecha_final_encuesta=DateTime::createFromFormat(
            'm/d/Y H:i A', 
            $request->input('fecha_final'))->format('Y-m-d H:i:s');
        $encuesta->descripcion_encuesta=$request->input('description');
        $encuesta->visible=0;

        
            

        if(isset($request->all()['visible']))
            $encuesta->visible = 1;
        $encuesta->save();
        //return back()->with('notification','Se registró exitosamente');
        return redirect()->action('EncuestaController@listado');

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
            //$intento = Intento::where('encuesta_id', $id_encuesta)->get();
            $clave = Clave::where('encuesta_id', $id_encuesta)->get();
            $encuesta = Encuesta::find($id_encuesta);

            $notificaicon = 'exito';
            $mensaje = 'La encuesta fue eliminada con éxito';

            //if(count($intento) || count($clave)){
            if(count($clave)){
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
