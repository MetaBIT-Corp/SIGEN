<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Encuesta;
use App\Area;
use App\Docente;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
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
    
     /**
     * Funcion para desplegar el formulario de crear encuesta
     * @param 
     * @author Edwin Palacios
     */
    public function getCreate(){
    	return view('encuesta.createEncuesta');
    }

    /**
     * Funcion para recuperar los datos del formulario de crear encuesta. 
     * Si son validos los datos los almacena en la base de datos 
     * @param 
     * @author Edwin Palacios
     */
    public function postCreate(Request $request){
    	//dd($request->all());
        
        $rules =[
            
            'title' => ['required', 'string','min:5','max:191'],
            'description' => ['required'],
            'fecha_inicio' => ['required', 
                function ($attribute, $value, $fail) {
                    $fecha_actual = Carbon::now('America/Denver')->format('d/m/Y h:i A');
                    if (($value < $fecha_actual)) {
                        $fail('La fecha inicial debe ser mayor a la actual.' );
                    }
                },
            ],
            'fecha_final' => ['required'],
        ];
        /* Mensaje de Reglas de Validación */
        $messages = [
            
            'title.required' => 'Debe de ingresar un título para la encuesta',
            'title.min' => 'El título debe contener como mínimo 5 caracteres',
            'description.required' => 'Debe de ingresar una descripción para la encuesta',
            'fecha_inicio.required' => 'Debe de indicar la fecha de inicio del periodo de disponibilidad',
            'fecha_final.required' => 'Debe de indicar la fecha de Fin del periodo de disponibilidad',

        ];
        
        //$this->validate($request,$rules,$messages);

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }


        $docente= Docente::where('user_id',auth()->user()->id)->first();
        $encuesta = new Encuesta();
        $encuesta->titulo_encuesta= $request->input('title');
        $encuesta->id_docente= $docente->id_pdg_dcn;
        $encuesta->fecha_inicio_encuesta= DateTime::createFromFormat(
            'd/m/Y H:i A', 
            $request->input('fecha_inicio'))->format('Y-m-d H:i:s');
        $encuesta->fecha_final_encuesta=DateTime::createFromFormat(
            'd/m/Y H:i A', 
            $request->input('fecha_final'))->format('Y-m-d H:i:s');
        if($encuesta->fecha_inicio_encuesta >= $encuesta->fecha_final_encuesta){
            return back()->with('danger', 'La fecha final debe ser mayor a la inicial')->withInput();
        }
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

    /**
     * Funcion para desplegar el formulario de editar encuesta
     * @param 
     * @author Edwin Palacios
     */
    public function getUpdate($id){

        $encuesta = Encuesta::find($id);
        $visibilidad = $encuesta->visible;

        $claves = Clave::where('encuesta_id', $id)->get();

        $encuesta->fecha_inicio_encuesta= DateTime::createFromFormat(
            'Y-m-d H:i:s',$encuesta->fecha_inicio_encuesta)->format('d/m/Y h:i A');
        $encuesta->fecha_final_encuesta=DateTime::createFromFormat(
            'Y-m-d H:i:s',$encuesta->fecha_final_encuesta)->format('d/m/Y h:i A');
        $fecha_actual = Carbon::now('America/Denver')->format('d/m/Y h:i A');

        $se_puede_editar=true;
        if($encuesta->fecha_inicio_encuesta<=$fecha_actual){
            $se_puede_editar=false;
        }

        /*Parte de René.*/

        $clave = Clave::where('encuesta_id',$id)->first();
        $docente = Docente::where('user_id',auth()->user()->id)->first();
        $areas = Area::where("id_pdg_dcn",$docente->id_pdg_dcn)->where('id_cat_mat',null)->get();
        $id_areas = Clave_Area::where('clave_id',$clave->id)->pluck('area_id')->toArray();

        return view('encuesta.updateEncuesta')->with(compact('encuesta','se_puede_editar', 'claves','areas','id_areas','clave', 'visibilidad'));

    }

    /**
     * Funcion para recuperar los datos del formulario de editar encuesta. 
     * Si son validos los datos los actualiza en la base de datos 
     * @param 
     * @author Edwin Palacios
     */
    public function postUpdate($id, Request $request){
        //dd($request->all());
        //si se puede editar la fecha inicial, va a evaluar si es mayor a la actual, sino, solamente hará validación de los demás campos
        if($request->input('se_puede_editar')){
            $rules =[
            
            'title' => ['required', 'string','min:5','max:191'],
            'description' => ['required'],
            'fecha_inicio' => ['required', 
                function ($attribute, $value, $fail) {
                    $fecha_actual = Carbon::now('America/Denver')->format('d/m/Y ´h:i A');
                    if (($value < $fecha_actual)) {
                        $fail('La fecha inicial debe ser mayor a la actual.' );
                    }
                },
            ],
            'fecha_final' => ['required' ],
        ];
        /* Mensaje de Reglas de Validación */
        $messages = [
            
            'title.required' => 'Debe de ingresar un título para la encuesta',
            'title.min' => 'El título debe contener como mínimo 5 caracteres',
            'description.required' => 'Debe de ingresar una descripción para la encuesta',
            'fecha_inicio.required' => 'Debe de indicar la fecha de inicio del periodo de disponibilidad',
            'fecha_final.required' => 'Debe de indicar la fecha de Fin del periodo de disponibilidad',
           
        ];
        }else{
             $rules =[
            
            'title' => ['required', 'string','min:5','max:191'],
            'description' => ['required','max:191'],
            'fecha_final' => ['required' ],
        ];
        /* Mensaje de Reglas de Validación */
        $messages = [
            
            'title.required' => 'Debe de ingresar un título para la encuesta',
            'title.min' => 'El título debe contener como mínimo 5 caracteres',
            'description.required' => 'Debe de ingresar una descripción para la encuesta',
            'fecha_final.required' => 'Debe de indicar la fecha de Fin del periodo de disponibilidad',
            'description.max' => 'Ha excedido el tamaño máximo de la descripción'
        ];
        }
        
        
        
        $this->validate($request,$rules,$messages);
        $docente= Docente::where('user_id',auth()->user()->id)->first();
        $encuesta = Encuesta::find($id);
        $encuesta->titulo_encuesta= $request->input('title');
        if($request->input('se_puede_editar')){
            $encuesta->fecha_inicio_encuesta= DateTime::createFromFormat(
            'd/m/Y H:i A', 
            $request->input('fecha_inicio'))->format('Y-m-d H:i:s');
        }
        $encuesta->fecha_final_encuesta=DateTime::createFromFormat(
            'd/m/Y H:i A', 
            $request->input('fecha_final'))->format('Y-m-d H:i:s');
        if($encuesta->fecha_inicio_encuesta >= $encuesta->fecha_final_encuesta){
            return back()->with('warning', 'La fecha final debe ser mayor a la inicial')->withInput();
        }
        $encuesta->descripcion_encuesta=$request->input('description');

        $encuesta->save();
        //return back()->with('notification','Se registró exitosamente');
        return redirect(URL::signedRoute('listado_encuesta'));;

    }


    /**
     * Función que lista las encuestas creadas de un docente 
     * a este listado solo pueden acceder los docentes y el administrador 
     * @param 
     * @author Edwin Palacios
     */
    public function listado(){
        
        if(auth()->user()->IsAdmin){
            $encuestas = Encuesta::all();
        }
        elseif(auth()->user()->IsTeacher){
            $encuestas=array();
            $docente= Docente::where('user_id',auth()->user()->id)->first();
            if($docente){
                $encuestas = Encuesta::where('id_docente',$docente->id_pdg_dcn)->get();
                //damos formato a fecha d/m/Y h:i A
                foreach ($encuestas as $encuesta) {
                    $encuesta->fecha_inicio_encuesta = $this->convertirFechaS($encuesta->fecha_inicio_encuesta);
                    $encuesta->fecha_final_encuesta = $this->convertirFechaS($encuesta->fecha_final_encuesta);
                }
            }
        }
    	return view('encuesta.listadoEncuesta')->with(compact('encuestas'));
    }

    /**
     * Función que lista las encuestas disponibles para contestar
     * @param 
     * @author Edwin Palacios
     */
    public function listado_publico(){
        $fecha_hora_actual = Carbon::now('America/Denver')->format('Y-m-d H:i:s');
        $encuestas = Encuesta::where('visible',1)
                        ->where('fecha_final_encuesta','>=', $fecha_hora_actual)
                        ->get();
        //recorremos las encuestas para dar a las fechas el formato m/d/Y h:i A 
        foreach ($encuestas as $encuesta) {
            $encuesta->fecha_inicio_encuesta = $this->convertirFechaS($encuesta->fecha_inicio_encuesta);
            $encuesta->fecha_final_encuesta = $this->convertirFechaS($encuesta->fecha_final_encuesta);
        
        }
        return view('encuesta.Encuestas')->with(compact('encuestas'));

    }

    /**
     * Función que elimina las encuestas
     * a esta funcion solo puede acceder el docente 
     * @param 
     * @author Enrique Menjivar
     */
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

    /**
     * Función para publicar la encuesta
     * a este listado solo pueden acceder los docentes y el administrador 
     * @param 
     * @author Edwin Palacios
     */
    public function publicar(Request $request){
        $todo_correcto = true;
        $id_encuesta = $request->input('id_encuesta_publicar');
        $notification = "exito";
        $message = "Éxito: Se ha publicado la encuesta de forma exitosa.";
        if($id_encuesta){
            $encuesta = Encuesta::find($id_encuesta); 

            //se verifica que si tiene una clave agregada
            if(Clave::where('encuesta_id', $encuesta->id)->exists()){
                    foreach ($encuesta->claves as $clave) {
                        //se verifica que tenga areas la clave
                        if(Clave_Area::where('clave_id', $clave->id)->exists()){
                            $clave_areas = $clave->clave_areas;
                            //se verifica que la clave area tenga clave area preguntas
                            foreach ($clave_areas as $clave_area) {
                                if($clave_area->claves_areas_preguntas->count()>0){
                                    $claves_areas_preguntas = $clave_area->claves_areas_preguntas;
                                    //se verifica que la clave area pregunta tenga pregunta
                                    foreach ($claves_areas_preguntas as $clave_area_pregunta) {
                                        if($clave_area_pregunta->pregunta->count()>0){
                                            $pregunta = $clave_area_pregunta->pregunta;
                                            //se verifica que la pregunta tengan opcion
                                                if($pregunta->opciones->count()>0){
                                                    
                                                }else{
                                                    $todo_correcto = false;
                                                    $notification = "error";
                                                    $message = "Error: Hay preguntas sin opciones";
                                                } 
                                        }else{
                                            $todo_correcto = false;
                                            $notification = "error";
                                            $message = "Error: No existen preguntas asignadas";
                                        }
                                    }
                                }else{
                                    $todo_correcto = false;
                                    $notification = "error";
                                    $message = "Error: Para la publicación debe agregar preguntas al área";
                                }
                            }     
                        }else{
                            $todo_correcto = false;
                            $notification = "error";
                            $message = "Error: Para la publicación debe agregar áreas de preguntas a la encuesta<br><br>";
                        }
                    }
                    
                }else{
                    $todo_correcto = false;
                    $notification = "error";
                    $message = "Error: no posee clave la encuesta";
                }  
        //si todo es correcto publica la encuesta, en caso contrario no. 
        if($todo_correcto){
          $encuesta->visible = 1; 
          $encuesta->save();  
        }
        
        }else{
            $notification = "error";
            $message = "Error: la acción no se realizó con éxito, vuelva a intentar";
        }
        return back()->with($notification,$message); 
    }

    /**
     * Función para permitir acceso a contestar la encuesta 
     * a este listado solo pueden acceder los docentes y el administrador 
     * @param 
     * @author Edwin Palacios
     */
    public function acceso(Request $request){
        $fecha_hora_actual = Carbon::now('America/Denver')->format('Y-m-d H:i:s');
        $id_clave = $request->input('id_clave');
        $id_encuesta_acceso = $request->input('id_encuesta_acceso');
        //dd($request->all());
        /*VALIDACIONES
        *
        *
        */
        if(Encuesta::find($id_encuesta_acceso)->exists()){
            $encuesta = Encuesta::find($id_encuesta_acceso);
            if($encuesta->fecha_inicio_encuesta <= $fecha_hora_actual &&
                $encuesta->fecha_final_encuesta > $fecha_hora_actual){
                return redirect()->action(
                        'IntentoController@iniciarEncuesta', 
                        ['id_clave' => $id_clave ]
                    );
            }
        }
        
        return back()->with('warning','Advertencia: La encuesta aún no se encuentra disponible');
    }



    /**
     * Funcion para convertir la fecha de formato que no tenga hasta los segundos
     * @param fecha
     * @author Edwin Palacios
     */
    public function convertirFechaS($fecha){
        $new_fecha = DateTime::createFromFormat('Y-m-d H:i:s',$fecha)->format('d/m/Y h:i A');
        return $new_fecha;
    }
    
}
