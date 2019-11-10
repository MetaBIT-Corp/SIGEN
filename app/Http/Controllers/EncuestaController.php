<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Encuesta;
use App\Area;
use App\Docente;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Intento;
use App\Pregunta;
use App\Clave;
use App\Clave_Area;
use App\Opcion;
use App\Grupo_Emparejamiento;
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


        if(isset($request->all()['visible'])){
            $encuesta->visible = 1;
        }
        if($archivo = $request->file('img_encuesta')){
            $nombre_archivo = $archivo->getClientOriginalName();
            $archivo->move('images',$nombre_archivo);
            $encuesta->ruta = $nombre_archivo;
        }
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
        if($archivo = $request->file('img_encuesta')){
            $nombre_archivo = $archivo->getClientOriginalName();
            $archivo->move('images',$nombre_archivo);
            $encuesta->ruta = $nombre_archivo;
        }

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
                                //obtenemos el area para saber que tipo de item es
                                $area = $clave_area->area;

                                if($clave_area->claves_areas_preguntas->count()>0){
                                    $claves_areas_preguntas = $clave_area->claves_areas_preguntas;
                                    //se verifica que la clave area pregunta tenga pregunta
                                    foreach ($claves_areas_preguntas as $clave_area_pregunta) {
                                        if($clave_area_pregunta->pregunta->count()>0){
                                            $pregunta = $clave_area_pregunta->pregunta;
                                            //se verifica que la pregunta tengan opcion. en este caso las de respuesta corta no deben de tener respuesta, por lo que la exluimos de esta evaluación
                                            if($area->tipo_item->id != 4){
                                               if($pregunta->opciones->count()>0 ){
                                                    
                                                }else{
                                                    $todo_correcto = false;
                                                    $notification = "error";
                                                    $message = "Error: Hay preguntas sin opciones";
                                                }  
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

    /**
     * Funcion para estadísticas de encuestas
     * @param 
     * @author Edwin Palacios
     */
    public function estadisticas($id){
        $id_encuesta = $id;
        $preguntas = $this->obtenerPreguntas($id_encuesta);
        
        $estadisticas = Array();
        
        if(Encuesta::where('id',$id_encuesta)->exists()){
            $encuesta = Encuesta::where('id',$id_encuesta)->first();
            if($encuesta->claves->count() >0){
                $claves = $encuesta->claves;
                foreach ($claves as $clave) {
                    foreach ($preguntas as $pregunta){
                        $total_encuestados = 0;
                        $array_opciones = array();
                        if(Opcion::where("pregunta_id",$pregunta->id)->exists()){
                            $opciones = Opcion::where("pregunta_id",$pregunta->id)->get();
                            foreach ($opciones as $opcion) {
                                
                                $cantidadRespuestas = $this->obtenerCantidadRespuestas($id_encuesta,$opcion->id,$clave->id);
                                $total_encuestados += $cantidadRespuestas;
                                $array_opciones[$opcion->id] = [
                                    'opcion' => $opcion->opcion,
                                    'cantidad' => $cantidadRespuestas,
                                    'porcentaje' => $this->obtenerPorcentaje($opciones,$opcion->id,$id_encuesta,$clave->id),
                                ];
                                
                            }
                        }
                        $respuestas = null;
                        $isRespuestaCorta = $this->isRespuestaCorta($pregunta);
                        //traemos respuestas de las preguntas de modalidad respuesta corta
                        if($isRespuestaCorta){
                            $respuestas = $this->obtenerRespuestas($id_encuesta, $pregunta->id,$clave->id);
                        }
                       $estadisticas[$pregunta->id]=[
                        'pregunta' => $pregunta->pregunta,
                        'opciones' => $array_opciones,
                        'encuestados' => $total_encuestados,
                        'respuesta_corta' => $isRespuestaCorta,
                        'respuestas' => $respuestas,
                       ]; 
                    }
                } 
            } 
        }
        
        //dd($estadisticas);
        return view('encuesta.estadisticasEncuesta')->with(compact('estadisticas','preguntas'));;
    }


    /**
     * Funcion para obtener las preguntas presentadas en una encuesta
     * @param $id_encuesta
     * @author Edwin Palacios
     */
    public function obtenerPreguntas($id_encuesta){
        $preguntas =DB::table('encuesta')
        ->join('clave','encuesta.id','=','clave.encuesta_id')
        ->join('clave_area','clave.id','=','clave_area.clave_id')
        ->join('clave_area_pregunta','clave_area.id','=','clave_area_pregunta.clave_area_id')
        ->join('pregunta','clave_area_pregunta.pregunta_id','=','pregunta.id')
        ->where([
            ['encuesta.id', '=', $id_encuesta],
        ])->select('pregunta.*')->get();
        return $preguntas;
    }

    /**
     * Funcion para obtener la cantidad de personas que seleccionaron una opción en específico
     * @param $id_encuesta
     * @param $id_opcion
     * @author Edwin Palacios
     */
    public function obtenerCantidadRespuestas($id_encuesta, $id_opcion,$id_clave){
        $cantidadRespuestas =DB::table('encuesta')
        ->join('clave','encuesta.id','=','clave.encuesta_id')
        ->join('clave_area','clave.id','=','clave_area.clave_id')
        ->join('clave_area_pregunta','clave_area.id','=','clave_area_pregunta.clave_area_id')
        ->join('pregunta','clave_area_pregunta.pregunta_id','=','pregunta.id')
        ->join('opcion','pregunta.id','=','opcion.pregunta_id')
        ->join('respuesta','opcion.id','=','respuesta.id_opcion')
        ->join('intento','respuesta.id_intento','=','intento.id')
        ->where([
            ['encuesta.id', '=', $id_encuesta],
            ['opcion.id', '=', $id_opcion],
            ['intento.clave_id', '=', $id_clave],
        ])->select('opcion.*')->count();
        return $cantidadRespuestas;
    }

     /**
     * Funcion para obtener la respuestas de los intentos
     * @param $id_encuesta
     * @param $id_opcion
     * @param $id_clave
     * @author Edwin Palacios
     */
    public function obtenerRespuestas($id_encuesta, $id_pregunta,$id_clave){
        $respuestas =DB::table('encuesta')
        ->join('clave','encuesta.id','=','clave.encuesta_id')
        ->join('clave_area','clave.id','=','clave_area.clave_id')
        ->join('clave_area_pregunta','clave_area.id','=','clave_area_pregunta.clave_area_id')
        ->join('pregunta','clave_area_pregunta.pregunta_id','=','pregunta.id')
        ->join('respuesta','pregunta.id','=','respuesta.id_pregunta')
        ->join('intento','respuesta.id_intento','=','intento.id')
        ->where([
            ['encuesta.id', '=', $id_encuesta],
            ['pregunta.id', '=', $id_pregunta],
            ['intento.clave_id', '=', $id_clave],
        ])->select('respuesta.texto_respuesta')->get();
        return $respuestas;
    }

    /**
     * Funcion para obtener el porcentaje obtenido
     * @param $opciones
     * @author Edwin Palacios
     */
    public function obtenerPorcentaje($opciones,$id_opcion,$id_encuesta,$id_clave){
        $cantidad_total=0;
        $cantidad_obtenida = 0;
        $porcentaje = 0;
        foreach ($opciones as $opcion) {
                    $cantidad_total += $this->obtenerCantidadRespuestas($id_encuesta,$opcion->id,$id_clave);
                    if($opcion->id == $id_opcion){
                        $cantidad_obtenida = $this->obtenerCantidadRespuestas($id_encuesta,$opcion->id,$id_clave);
                    }   
                }
        if($cantidad_total != 0){
            $porcentaje = ($cantidad_obtenida*100)/$cantidad_total;
        }
        return $porcentaje;
    }

    /**
     * Funcion que devuelve un boolean true si la pregunta que se le pasa por parametro 
     * pertenece a un área de respuesta corta
     * @param $pregunta
     * @author Edwin Palacios
     */
    public function isRespuestaCorta($pregunta){
        $is_respuesta_corta= false;
        $grupo_emp = Grupo_Emparejamiento::where('id', $pregunta->grupo_emparejamiento_id)->first();
        if($grupo_emp->area->tipo_item->id == 4){
            $is_respuesta_corta= true;
        }
        return $is_respuesta_corta;
    }

    
}
