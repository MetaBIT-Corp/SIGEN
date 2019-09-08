<?php

namespace App\Http\Controllers;

use App\Turno;
use App\Evaluacion;
use App\Clave;
use App\CargaAcademica;
use App\CicloMateria;
use App\Estudiante;
use App\Clave_Area;
use App\Intento;
use App\Area;
use App\Encuesta;
use App\Encuestado;
use App\Pregunta;
use App\Clave_Area_Pregunta;
use App\Clave_Area_Pregunta_Estudiante;
use App\Opcion;
use App\Grupo_Emparejamiento;
use Illuminate\Http\Request;
use DateTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

    
class TurnoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $evaluacion = Evaluacion::find($id);
        $turnos = $evaluacion->turnos;
        $nombre_evaluacion = $evaluacion->nombre_evaluacion;
        $evaluacion_id = $evaluacion->id;
        
        foreach($turnos as $turno){
            
            $fecha_hora_actual = Carbon::now('America/Denver')->format('Y-m-d H:i:s');
            
             if(!Carbon::parse($turno->fecha_final_turno)->gt(Carbon::parse($fecha_hora_actual)))
                 $turno['acciones'] = false;
             else
                 $turno['acciones'] = true;
            
            if(!Carbon::parse($turno->fecha_inicio_turno)->gt(Carbon::parse($fecha_hora_actual)))
                 $turno['accion_delete'] = false;
             else
                 $turno['accion_delete'] = true;
                 
                 
            $turno->fecha_inicio_turno = DateTime::createFromFormat('Y-m-d H:i:s', $turno->fecha_inicio_turno)->format('d/m/Y h:i A');
            $turno->fecha_final_turno = DateTime::createFromFormat('Y-m-d H:i:s', $turno->fecha_final_turno)->format('d/m/Y h:i A');
            
        }
        
        //dd($turnos);
        
        return view('turno.index', compact('turnos','nombre_evaluacion','evaluacion_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        if(!Evaluacion::find($id))
            return redirect('/home');
        
        return view('turno.create', compact('id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($id, Request $request)
    {
        $requestData = $request->all();
        $requestData['evaluacion_id'] = $id;
        
        $rules = [
            'fecha_inicio_turno' => 'required',
            'fecha_final_turno' => 'required',
            'contraseña' => 'required|min:8'
        ];
        
        $messages = [
            'contraseña.required' => 'La contraseña es requerida.',
            'contraseña.min' => 'La contraseña debe presentar como mínimo 8 caracteres.',
            'fecha_inicio_turno.required' => 'La fecha/hora de inicio es requerida.',
            'fecha_final_turno.required' => 'La fecha/hora de fin es requerida.'
        ];
        
        $validator = Validator::make($requestData, $rules, $messages);
        
        

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $requestData['fecha_inicio_turno'] = DateTime::createFromFormat('d/m/Y H:i A', $request->input('fecha_inicio_turno'))->format('Y-m-d H:i:s');
        $requestData['fecha_final_turno'] = DateTime::createFromFormat('d/m/Y H:i A', $request->input('fecha_final_turno'))->format('Y-m-d H:i:s');
        
        if(!Carbon::parse($requestData['fecha_final_turno'])->gt(Carbon::parse($requestData['fecha_inicio_turno'])))
            return back()->with('notification-type','danger')->with('notification-message','La fecha/hora de fin debe ser mayor que la fecha/hora de inicio.')->withInput();
        
        $fecha_hora_actual = Carbon::now('America/Denver')->format('Y-m-d H:i:s');
        $fecha_hora_actual_alert = DateTime::createFromFormat('Y-m-d H:i:s', $fecha_hora_actual)->format('d/m/Y h:i A');
            
        if(!Carbon::parse($requestData['fecha_inicio_turno'])->gt(Carbon::parse($fecha_hora_actual)))
            return back()->with('notification-type','danger')->with('notification-message','La fecha/hora de inicio debe ser mayor que la fecha/hora actual ('.$fecha_hora_actual_alert.').')->withInput();
        
        $diff_fin_inicio = Carbon::parse($requestData['fecha_final_turno'])->diffInHours(Carbon::parse($requestData['fecha_inicio_turno']));
        $duracion_evaluacion = Evaluacion::find($requestData['evaluacion_id'])->duracion;
                
        if(! (($diff_fin_inicio - $duracion_evaluacion) >= 0) )
            return back()->with('notification-type','danger')->with('notification-message','La diferencia en horas entre la fecha/hora de fin y la fecha/hora de inicio debe ser mayor que la duración de la evaluación ('. $duracion_evaluacion.' horas).')->withInput();

        $turno = new Turno();
        $turno->fecha_inicio_turno = $requestData['fecha_inicio_turno'];
        $turno->fecha_final_turno = $requestData['fecha_final_turno'];
        $turno->contraseña = bcrypt($requestData['contraseña']);
        $turno->evaluacion_id = $requestData['evaluacion_id'];
        $turno->visibilidad = 0;

        if(isset($requestData['visibilidad']))
            $turno->visibilidad = 1;
        
        $turno->save();

        $clave = new Clave();

        $clave->turno_id = $turno->id;
        $clave->numero_clave = 1;

        $clave->save();
        
        return back()->with('notification-type','success')->with('notification-message','El turno se ha registrado con éxito!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Turno  $turno
     * @return \Illuminate\Http\Response
     */
    public function show(Turno $turno)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Turno  $turno
     * @return \Illuminate\Http\Response
     */
    public function edit( $id, $turno_id )
    {
        //Obteniendo la clave del turno
        $claves = Clave::where('turno_id', $turno_id)->get();   /*Consulta ahora se hace por turno_id*/

        //dd(count($claves[0]->clave_areas[0]->claves_areas_preguntas));

        $fecha_hora_actual = Carbon::now('America/Denver')->format('Y-m-d H:i:s');
        
        $turno = Turno::find($turno_id);
        $turno['iniciado'] = false;
        
        if(!Carbon::parse($turno->fecha_inicio_turno)->gt(Carbon::parse($fecha_hora_actual)))
            $turno['iniciado'] = true;
            
        
        $turno->fecha_inicio_turno = DateTime::createFromFormat('Y-m-d H:i:s', $turno->fecha_inicio_turno)->format('d/m/Y h:i A');
        $turno->fecha_final_turno = DateTime::createFromFormat('Y-m-d H:i:s', $turno->fecha_final_turno)->format('d/m/Y h:i A');

        // Parte de René

        $clave = Clave::where('turno_id',$turno_id)->first();
        $evaluacion = Evaluacion::where('id',$turno->evaluacion_id)->first();
        $carga = CargaAcademica::where('id_carg_aca',$evaluacion->id_carga)->first();
        $materiac = CicloMateria::where('id_mat_ci',$carga->id_mat_ci)->first();
        $areas = Area::where("id_cat_mat",$materiac->id_mat_ci)->get();
        $id_areas = Clave_Area::where('clave_id',$clave->id)->pluck('area_id')->toArray();

        return view('turno.edit', compact('turno', 'id', 'claves', 'clave','evaluacion','carga','materiac','areas','id_areas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Turno  $turno
     * @return \Illuminate\Http\Response
     */
    public function update($evaluacion_id, Request $request, $id)
    {
        $requestData = $request->all();
        
        $rules = [
            'fecha_inicio_turno' => 'required',
            'fecha_final_turno' => 'required',
            'contraseña' => 'nullable|min:8'
        ];
        
        $messages = [
            'contraseña.min' => 'La contraseña debe presentar como mínimo 8 caracteres.',
            'fecha_inicio_turno.required' => 'La fecha/hora de inicio es requerida.',
            'fecha_final_turno.required' => 'La fecha/hora de fin es requerida.'
        ];
        
        $validator = Validator::make($requestData, $rules, $messages);
        
        

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $requestData['fecha_inicio_turno'] = DateTime::createFromFormat('d/m/Y H:i A', $request->input('fecha_inicio_turno'))->format('Y-m-d H:i:s');
        $requestData['fecha_final_turno'] = DateTime::createFromFormat('d/m/Y H:i A', $request->input('fecha_final_turno'))->format('Y-m-d H:i:s');
        
        if(!Carbon::parse($requestData['fecha_final_turno'])->gt(Carbon::parse($requestData['fecha_inicio_turno'])))
            return back()->with('notification-type','danger')->with('notification-message','La fecha/hora de fin debe ser mayor que la fecha/hora de inicio.')->withInput();
        
        $fecha_hora_actual = Carbon::now('America/Denver')->format('Y-m-d H:i:s');
        $fecha_hora_actual_alert = DateTime::createFromFormat('Y-m-d H:i:s', $fecha_hora_actual)->format('d/m/Y h:i A');
        
        if(!$requestData["iniciado"]){
            
            if(!Carbon::parse($requestData['fecha_inicio_turno'])->gt(Carbon::parse($fecha_hora_actual)))
                return back()->with('notification-type','danger')->with('notification-message','La fecha/hora de inicio debe ser mayor que la fecha/hora actual ('.$fecha_hora_actual_alert.').')->withInput();
            
        }
        
        $diff_fin_inicio = Carbon::parse($requestData['fecha_final_turno'])->diffInHours(Carbon::parse($requestData['fecha_inicio_turno']));
        $duracion_evaluacion = Evaluacion::find($evaluacion_id)->duracion;
                
        if(! (($diff_fin_inicio - $duracion_evaluacion) >= 0) )
            return back()->with('notification-type','danger')->with('notification-message','La diferencia en horas entre la fecha/hora de fin y la fecha/hora de inicio debe ser mayor que la duración de la evaluación ('. $duracion_evaluacion.' horas).')->withInput();
        
        $turno = Turno::find($id);
        $turno->fecha_inicio_turno = $requestData['fecha_inicio_turno'];
        $turno->fecha_final_turno = $requestData['fecha_final_turno'];
        
        if(isset($requestData['contraseña']) and $requestData['contraseña'] != null)
           $turno->contraseña = bcrypt($requestData['contraseña']);
        
        $turno->visibilidad = 0;

        if(isset($requestData['visibilidad']))
            $turno->visibilidad = 1;
        
        $turno->save();
        
        return back()->with('notification-type','success')->with('notification-message','El turno se ha actualizado con éxito!');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Turno  $turno
     * @return \Illuminate\Http\Response
     */
    public function destroy($evaluacion_id, $id)
    {
        Turno::find($id)->delete();
        return back();
    }
    
    public function getDuracionEvaluacion($evaluacion_id)
    {
        return Evaluacion::find($evaluacion_id)->duracion;
    }
    
    public function getEvaluacion($turno_id, $estudiante_id){
        
        //Array asociativo que se enviara como respuesta
        $evaluacion = array();
        
        //Obtenemos la clave que corresponde al turno que el estudiante a indicado que desea descargar
        $clave = Clave::where('turno_id', $turno_id)->first();
        $evaluacion['clave'] = $clave;
            
        //Obtenemos al estudiante
        $estudiante = Estudiante::where('id_est',$estudiante_id)->first();
        
        //Creamos el intento, para luego ser enviado a la aplicación móvil
        $intento = new Intento();
        $intento->estudiante_id = $estudiante->id_est;
        $intento->clave_id = $clave->id;
        $intento->encuesta_id = null;
        $intento->fecha_inicio_intento = Carbon::now('America/Denver')->format('Y-m-d H:i:s');
        $intento->save();
        $evaluacion['intento'] = $intento;
        
        //Obtenemos las clave_area, esto significa obtener las areas que corresponden a la clave
        $clave_areas = Clave_Area::where('clave_id', $clave->id)->get();
        
        //Vamos a recorrer los objetos clave_areas para obtener los objetos relacionados con cada uno de ellos
        $clave_areas_arr = array();
        $areas_arr = array();
        $grupos_emp_arr = array();
        $preguntas_arr = array();
        $opciones_arr = array();
        $clave_area_preguntas_arr = array();
        
        foreach($clave_areas as $clave_area){
            //Almacenamos el objeto en un Array
            $clave_areas_arr[] = $clave_area;
            
            //Procederemos a obtener cada objeto Área
            $areas_arr[] = Area::find($clave_area->area_id);
            
            //Procedemos a obtener las preguntas que se le han asignado al estudiante para esta clave_area que se esta recorriendo
            $clave_area_preguntas = Clave_Area_Pregunta_Estudiante::where('clave_area_id',$clave_area->id)->where('estudiante_id',$estudiante->id_est)->get();
            
            foreach($clave_area_preguntas as $clave_area_pregunta){
                //Almacenamos esta relacion ya que se necesita esta en la BD del móvil clave_area_pregunta
                $clave_area_preguntas_arr[] = $clave_area_pregunta;
                
                //Buscamos la Pregunta, ya que es necesario agregarla al Array correspondiente
                $pregunta = Pregunta::find($clave_area_pregunta->pregunta_id);
                
                //Almacenamos el objeto Pregunta en un array
                $preguntas_arr[] = $pregunta;
                
                //Procederemos a obtener las opciones de la pregunta
                $opciones = Opcion::where('pregunta_id', $pregunta->id)->get();
                
                //Vamos a recorrer este array de opciones para almacenar uno por uno en el Array opciones_arr
                foreach($opciones as $opcion){
                    $opciones_arr[] = $opcion;
                }
                
                //Procederemos a obtener el Grupo_Emparejamiento de la Pregunta
                $gpo_emp = Grupo_Emparejamiento::find($pregunta->grupo_emparejamiento_id);
                
                //Debemos validar que este Grupo_Emparejamiento no este repetido en el Array grupos_emp_arr, ya que varias preguntas pueden apuntar a uno, y solo necesitamos un objeto
                if($grupos_emp_arr == [])
                    $grupos_emp_arr[] = $gpo_emp; //Si esta vacio, lo agregamos
                else{
                    //Si hay objetos, hay que recorrerlo y verificar que no se repita
                    $repetido = false;
                    foreach($grupos_emp_arr as $grupo_emp){
                        
                        if($gpo_emp->id == $grupo_emp->id){
                            $repetido = true;
                            break; //Esto indica que esta repetido, en este caso ya no es necesario seguir buscando, por lo que rompemos el bucle
                        }
                        
                            
                    }
                    
                    if(! $repetido)
                        $grupos_emp_arr[] = $gpo_emp; //Si no esta repetido lo agregamos
                }
                
            }
                 
            
        }
        
        //Agregamos los Arrays que obtuvimos de los bucles anteriores al Array que se enviara como respuesta 
        
        $evaluacion['clave_areas'] = $clave_areas_arr;
        $evaluacion['areas'] = $areas_arr;
        $evaluacion['clave_area_preguntas'] = $clave_area_preguntas_arr;
        $evaluacion['grupos_emp'] = $grupos_emp_arr;
        $evaluacion['preguntas'] = $preguntas_arr;
        $evaluacion['opciones'] = $opciones_arr;
        
        return $evaluacion;   
        
    }
    
    public function getEncuesta($encuesta_id, $mac){
        $encuesta_arr = array();
        //Primero obtenemos el objeto de Encuesta
        $encuesta = Encuesta::find($encuesta_id);
        $encuesta_arr['encuesta'] = $encuesta;
        
        //Buscamos si esta dirección MAC ya se encuentra registrada
        $encuestados = Encuestado::where('MAC',$mac)->get();
        $encuestado = null;
    
        if($encuestados->count())
            //En caso que la consulta anterior retorne objetos de Encuestado, obtenemos el primero
            $encuestado = $encuestados[0];
        else{
            //Si no hay ninguno registrado con esta MAC lo creamos
            $encuestado = new Encuestado();
            $encuestado->MAC = $mac;
            $encuestado->save();
        }
        //Ahora almacenamos en el Array al Encuestado
        $encuesta_arr['encuestado'] = $encuestado;
        
        //Procederemos a obtener la Clave, la cual se relaciona con la Encuesta directamente
        //Se asume por el momento que una Encuesta solamente poseera una Clave
        $clave = Clave::where('encuesta_id', $encuesta->id)->first();
        $encuesta_arr['clave'] = $clave;
        
        //Procederemos a crear el Intento
        $intento = new Intento(); 
        $intento->estudiante_id = null;
        $intento->encuestado_id = $encuestado->id;
        $intento->clave_id = $clave->id;
        //$intento->encuesta_id = $encuesta->id;
        $intento->fecha_inicio_intento = Carbon::now('America/Denver')->format('Y-m-d H:i:s');
        $intento->save();
        
        $encuesta_arr['intento'] = $intento;
        
        //Creamos Arrays que se enviaran
        $clave_areas_arr = array();
        $areas_arr = array();
        $grupos_emp_arr = array();
        $preguntas_arr = array();
        $opciones_arr = array();
        $clave_area_preguntas_arr = array();
        
        //Procederemos ahora a obtener las Clave_Areas
        $clave_areas = Clave_Area::where('clave_id', $clave->id)->get();
        
        foreach($clave_areas as $clave_area){
            $clave_areas_arr[] = $clave_area;
            
            //Procederemos a obtener cada objeto Área
            $areas_arr[] = Area::find($clave_area->area_id);
            
            //Procedemos a obtener las preguntas que se le han asignado al estudiante para esta clave_area que se esta recorriendo
            $clave_area_preguntas = Clave_Area_Pregunta::where('clave_area_id',$clave_area->id)->get();
            
            foreach($clave_area_preguntas as $clave_area_pregunta){
                //Almacenamos esta relacion ya que se necesita esta en la BD del móvil clave_area_pregunta
                $clave_area_preguntas_arr[] = $clave_area_pregunta;
                
                //Buscamos la Pregunta, ya que es necesario agregarla al Array correspondiente
                $pregunta = Pregunta::find($clave_area_pregunta->pregunta_id);
                
                //Almacenamos el objeto Pregunta en un array
                $preguntas_arr[] = $pregunta;
                
                //Procederemos a obtener las opciones de la pregunta
                $opciones = Opcion::where('pregunta_id', $pregunta->id)->get();
                
                //Vamos a recorrer este array de opciones para almacenar uno por uno en el Array opciones_arr
                foreach($opciones as $opcion){
                    $opciones_arr[] = $opcion;
                }
                
                //Procederemos a obtener el Grupo_Emparejamiento de la Pregunta
                $gpo_emp = Grupo_Emparejamiento::find($pregunta->grupo_emparejamiento_id);
                
                //Debemos validar que este Grupo_Emparejamiento no este repetido en el Array grupos_emp_arr, ya que varias preguntas pueden apuntar a uno, y solo necesitamos un objeto
                if($grupos_emp_arr == [])
                    $grupos_emp_arr[] = $gpo_emp; //Si esta vacio, lo agregamos
                else{
                    //Si hay objetos, hay que recorrerlo y verificar que no se repita
                    $repetido = false;
                    foreach($grupos_emp_arr as $grupo_emp){
                        
                        if($gpo_emp->id == $grupo_emp->id){
                            $repetido = true;
                            break; //Esto indica que esta repetido, en este caso ya no es necesario seguir buscando, por lo que rompemos el bucle
                        }
                        
                            
                    }
                    
                    if(! $repetido)
                        $grupos_emp_arr[] = $gpo_emp; //Si no esta repetido lo agregamos
                }
                
            }
        }
        
        //Agregamos los Arrays que obtuvimos de los bucles anteriores al Array que se enviara como respuesta 
        
        $encuesta_arr['clave_areas'] = $clave_areas_arr;
        $encuesta_arr['areas'] = $areas_arr;
        $encuesta_arr['grupos_emp'] = $grupos_emp_arr;
        $encuesta_arr['preguntas'] = $preguntas_arr;
        $encuesta_arr['opciones'] = $opciones_arr;
        $encuesta_arr['clave_area_preguntas'] = $clave_area_preguntas_arr;
        
        return $encuesta_arr;
    }
    
}
