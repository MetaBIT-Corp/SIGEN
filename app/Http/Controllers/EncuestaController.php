<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Encuesta;
use App\Area;
use App\Docente;
use Carbon\Carbon;
use App\Intento;
use App\Clave;
use App\Clave_Area;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use DateTime;

class EncuestaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
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

        //creacion de clave
        $clave = new Clave();
        $clave->encuesta_id = $encuesta->id;
        $clave->numero_clave = 1;
        $clave->save();

        //return back()->with('notification','Se registró exitosamente');
        return redirect(URL::signedRoute('listado_encuesta'));;

    }

    public function getUpdate($id){

        $encuesta = Encuesta::find($id);

        $claves = Clave::where('encuesta_id', $id)->get();

        $encuesta->fecha_inicio_encuesta= DateTime::createFromFormat(
            'Y-m-d H:i:s',$encuesta->fecha_inicio_encuesta)->format('m/d/Y g:i A');
        $encuesta->fecha_final_encuesta=DateTime::createFromFormat(
            'Y-m-d H:i:s',$encuesta->fecha_final_encuesta)->format('m/d/Y g:i A');
        $fecha_actual = Carbon::now('America/Denver')->format('m/d/Y g:i A');

        $se_puede_editar=true;
        if($encuesta->fecha_inicio_encuesta<=$fecha_actual){
            $se_puede_editar=false;
        }

        /*Parte de René.*/

        $clave = Clave::where('encuesta_id',$id)->first();
        $docente = Docente::where('user_id',auth()->user()->id)->first();
        $areas = Area::where("id_pdg_dcn",$docente->id_pdg_dcn)->where('id_cat_mat',null)->get();
        $id_areas = Clave_Area::where('clave_id',$clave->id)->pluck('area_id')->toArray();

        return view('encuesta.updateEncuesta')->with(compact('encuesta','se_puede_editar', 'claves','areas','id_areas','clave'));

    }

    public function postUpdate($id, Request $request){
        //dd($request->all());
        if($request->input('se_puede_editar')){
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
        }else{
             $rules =[
            
            'title' => ['required', 'string','min:5','max:191'],
            'description' => ['required','max:191'],
            'fecha_final' => ['required' , 'date', 'after:fecha_inicio'],
        ];
        /* Mensaje de Reglas de Validación */
        $messages = [
            
            'title.required' => 'Debe de ingresar un título para la encuesta',
            'title.min' => 'El título debe contener como mínimo 5 caracteres',
            'description.required' => 'Debe de ingresar una descripción para la encuesta',
            'fecha_final.required' => 'Debe de indicar la fecha de Fin del periodo de disponibilidad',
            'fecha_final.after' => 'La fecha final debe ser mayor a la fecha inicial ',
            'description.max' => 'Ha excedido el tamaño máximo de la descripción'
        ];
        }
        
        
        
        $this->validate($request,$rules,$messages);
        $docente= Docente::where('user_id',auth()->user()->id)->first();
        $encuesta = Encuesta::find($id);
        $encuesta->titulo_encuesta= $request->input('title');
        if($request->input('se_puede_editar')){
            $encuesta->fecha_inicio_encuesta= DateTime::createFromFormat(
            'm/d/Y H:i A', 
            $request->input('fecha_inicio'))->format('Y-m-d H:i:s');
        }
        $encuesta->fecha_final_encuesta=DateTime::createFromFormat(
            'm/d/Y H:i A', 
            $request->input('fecha_final'))->format('Y-m-d H:i:s');
        $encuesta->descripcion_encuesta=$request->input('description');

        $encuesta->save();
        //return back()->with('notification','Se registró exitosamente');
        return redirect(URL::signedRoute('listado_encuesta'));;

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
        $fecha_hora_actual = Carbon::now('America/Denver')->format('Y-m-d H:i:s');
        $encuestas = Encuesta::where('visible',1)
                        ->where('fecha_inicio_encuesta','<=', $fecha_hora_actual)
                        ->get();
        foreach ($encuestas as $encuesta) {
            $encuesta->fecha_inicio_encuesta = $this->convertirFechaS($encuesta->fecha_inicio_encuesta);
            $encuesta->fecha_final_encuesta = $this->convertirFechaS($encuesta->fecha_final_encuesta);
        
        }
        
        return view('encuesta.Encuestas')->with(compact('encuestas'));

    }

    public function eliminarEncuesta(Request $request){
        $id_encuesta = $request->input('id_encuesta');

        if($id_encuesta){
            $clave = Clave::where('encuesta_id', $id_encuesta)->get();
            $encuesta = Encuesta::find($id_encuesta);

            $notificaicon = 'exito';
            $mensaje = 'La encuesta fue eliminada con éxito';
            
            if(count($clave[0]->intentos)){
                $notificaicon = 'error';
                $mensaje = 'Esta encuesta no se puede eliminar porque ya fue asignada';                

            }
            else{
                if(count($encuesta->claves)){
                    foreach ($encuesta->claves as $clave) {
                        
                        if(count($clave->clave_areas)){
                            foreach ($clave->clave_areas as $ca) {
                                
                                if(count($ca->claves_areas_preguntas)){
                                    foreach ($ca->claves_areas_preguntas as $cap) {
                                        $cap->delete();
                                    }
                                }

                                $ca->delete();
                            }
                        }

                        $clave->delete();
                    }
                }

                $encuesta->delete();
            }   
        }

        return back()->with($notificaicon, $mensaje);
    }

    public function publicar(Request $request){
       
        $id_encuesta = $request->input('id_encuesta_publicar');
        $notification = "exito";
        $message = "Éxito: Se ha publicado la encuesta de forma exitosa.";
        if($id_encuesta){
            $encuesta = Encuesta::find($id_encuesta); 
            $encuesta->fecha_inicio_encuesta= DateTime::createFromFormat(
                    'Y-m-d H:i:s',
                    $encuesta->fecha_inicio_encuesta
                )->format('l jS \\of F Y h:i A');
            $encuesta->fecha_final_encuesta= DateTime::createFromFormat(
                    'Y-m-d H:i:s',
                    $encuesta->fecha_final_encuesta
                )->format('l jS \\of F Y h:i A');
            //se verifica que si tiene una clave agregada
            if(Clave::where('encuesta_id', $encuesta->id)->exists()){
                    foreach ($encuesta->claves as $clave) {
                        //se verifica que tenga areas la clave
                        if(Clave_Area::where('clave_id', $clave->id)->exists()){
                                $encuesta->visible = 1; 
                                $encuesta->fecha_inicio_encuesta= DateTime::createFromFormat(
                                        'l jS \\of F Y h:i A',
                                        $encuesta->fecha_inicio_encuesta
                                    )->format('Y-m-d H:i:s');
                                $encuesta->fecha_final_encuesta= DateTime::createFromFormat(
                                        'l jS \\of F Y h:i A',
                                        $encuesta->fecha_final_encuesta
                                    )->format('Y-m-d H:i:s');
                                $encuesta->save();
                        }else{
                            $notification = "error";
                            $message = "Error: Para la publicación debe agregar áreas de preguntas a la encuesta<br><br>";
                        }
                    }
                    
                }else{
                    $notification = "error";
                    $message = "Error: no posee clave la encuesta";
                }  
            
            
        }else{
            $notification = "error";
            $message = "Error: la acción no se realizó con éxito, vuelva a intentar";
        }
        return back()->with($notification,$message); 
    }
    public function acceso(Request $request){
        
        $id_clave = $request->input('id_clave');
        //dd($id_clave);
        /*VALIDACIONES
        *
        *
        */
        return redirect()->action(
                        'IntentoController@iniciarEncuesta', 
                        ['id_clave' => $id_clave ]
                    );
    }
    /**
     * Funcion para convertir la fecha de formato que no tenga hasta los segundos
     * @param fecha
     * @author Edwin Palacios
     */
    public function convertirFechaS($fecha){
        $new_fecha = DateTime::createFromFormat('Y-m-d H:i:s',$fecha)->format('Y-m-d H:i');
        return $new_fecha;
    }
    
}
