<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Encuesta;
use App\Turno;
use App\Evaluacion;
use App\Clave;
use App\Estudiante;
use App\Clave_Area;
use App\Intento;
use App\Area;
use App\Encuestado;
use App\Pregunta;
use App\Clave_Area_Pregunta;
use App\Clave_Area_Pregunta_Estudiante;
use App\Opcion;
use App\Grupo_Emparejamiento;
use App\CargaAcademica;
use App\User;
use App\Respuesta;

class ApiController extends Controller
{
    
	/*--------------------------Modelo Encuesta--------------------------*/
    //Funcion rotorna las encuestas de propósito general que se encuentra disponibles en formato JSON  
    public function encuestasDisponibles(){
        $fecha_hora_actual = Carbon::now('America/Denver')->addMinutes(10)->format('Y-m-d H:i:s');
        $encuestas = Encuesta::whereDate('fecha_final_encuesta', '>', $fecha_hora_actual)->get();

        //dd($encuestas);
        $data = ['encuestas'=>$encuestas];
        return $data;
    }

    //Funcion que es llamada cuando finaliza el intento en el móvil
    public function finalizarIntentoMovil(Request $request){
        $respuesta = new Respuesta();
        $es_encuesta=$request->es_encuesta;
        
        //cantidad total de preguntas que vienen desde el móvil
        $total_preguntas = $request->total_preguntas;

        //Obteniendo los valores del request y asignandolos a la tabla respuesta
        $respuesta->id_pregunta = $request->pregunta_id;        //pregunts
        $respuesta->id_opcion = $request->opcion_id;            //opcion
        $respuesta->id_intento = $request->intento_id;          //intento 
        $respuesta->texto_respuesta = $request->texto_respuesta;//texto escrito en caso sea respues corta

        //Guardar el objeto respuesta
        $respuesta->save();

        //Consulta la cantidad de respuestas que ha sido guardadas del intento correspondiente
        $num_actual = Respuesta::where('id_intento', $request->intento_id)->get();

        //Verifica si todas las respuestas que venian del movil ya se guardaron en la base de datos mysql
        if($total_preguntas == count($num_actual)){
        	$intento = Intento::find($request->intento_id);

        	if($es_encuesta==1){
        		//Actualizar los datos del intento correspondiente
	            $fecha_hora_actual = Carbon::now('America/Denver')->format('Y-m-d H:i:s');
	            $intento->fecha_final_intento = $fecha_hora_actual;
	            $intento->save();
        	}else{
        		//Lama al método calcular nota
	            $nota = $intento->calcularNota($request->intento_id);

	            //Actualizar los datos del intento correspondiente
	            $fecha_hora_actual = Carbon::now('America/Denver')->format('Y-m-d H:i:s');
	            $intento->nota_intento = $nota;
	            $intento->fecha_final_intento = $fecha_hora_actual;
	            $intento->save();
        	}
        }
    }

    /**
     * Funcion para cargar los turnos de una evaluacion mediante AJAX 
     *(Utilizada para mostrar los turnos a publicar).
     * @param int $id id de la evaluacion
     * @author Edwin Palacios
     */
    public function turnosPorEvaluacion($id){
        $turnos = Turno::where('evaluacion_id', $id)->get();
        $data = ['turnos'=>$turnos];
        return $data;
    }

    /**
     * Metodo que devuelve las evaluaicones y turnos disponibles (MOVIL)..
     * @author Edwin Palacios
     * @param id_carga que corresponde al id de la carga academica del estudiante
     * @return Json que contiene las evaluaciones y turnos disponibles.
     */ 
    public function evaluacionTurnosDisponibles($id_carga){
    	//declaración de variables
        $fecha_hora_actual = Carbon::now('America/Denver')->addMinutes(10)->format('Y-m-d H:i:s');
        $evaluaciones = array();
        $turnos = array();
            //verificamos si existe la carga academica
            if(CargaAcademica::where('id_carg_aca',$id_carga)->exists()){
                //obtenemos todas las cargas academicas de la materia, con el objetivo de presentar todas las evaluaciones de los docentes en la materia
                $carga_academica = CargaAcademica::where('id_carg_aca',$id_carga)->first();
                $ciclo_materia = $carga_academica->materiaCiclo;
                $cargas_academicas = $ciclo_materia->cargas;
                foreach ($cargas_academicas as $carga) {
                    $evaluaciones_all = Evaluacion::where('id_carga',$carga->id_carg_aca)
                                ->where('habilitado',1)
                                ->get();
                    //verificacion de que las evaluaciones que se manden a la vista, poseean al menos un turno disponible
                    foreach ($evaluaciones_all as $evaluacion) {
                        $turnos_activos = false;
                        if($evaluacion->turnos){
                            foreach ($evaluacion->turnos as $turno) {
                                if($turno->visibilidad==1 && 
                                    $turno->fecha_final_turno > $fecha_hora_actual){
                                    $turnos_activos = true;
                                    $turnos[] = $turno;
                                }
                            }
                            if($turnos_activos==true){
                                $evaluaciones[] = $evaluacion;
                            }
                        }
                    }
                }
                
            } 
        $data = [
            'evaluaciones'=>$evaluaciones,
            'turnos' => $turnos];
        return response()->json(
            $data,
             200,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], 
            JSON_UNESCAPED_UNICODE);
    }

    /**
     * Metodo que devuelve la consulta del usuario solicitado (MOVIL).
     * @author Edwin Palacios
     * @param email del usuario
     * @param password del usuario
     * @return Json que contiene el registro del user y el estudiante vinculado a ese user.
     */ 
    public function accesoUserMovil($email, $password){
        $estudiante = null;
        $user_autenticado = null;
        if( User::where('email',$email)->exists()){
            $user_no_autenticado = User::where('email',$email)->first();
        	if($user_no_autenticado->IsStudent && Hash::check($password, $user_no_autenticado->password)){
            	$user_autenticado = $user_no_autenticado;
                $user_autenticado->name = $password;
                $estudiante = Estudiante::where('user_id',$user_autenticado->id)->first();
            }
        }
        
        $data = [
            'user'=>$user_autenticado,
            'estudiante'=>$estudiante];
        return $data;
    }
    /*
     * Funcion para web service de materias que cursa un determinado estudiante
     * @param int $id_user ID del usuario del estudiante
     * @author Ricardo Estupinian
     */
    public function getMateriasEstudiante($id_user){
        $materias=MateriaController::materiasEstudiante($id_user);
        //dd($materias);
        return response()->Json($materias);
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
        $intento = IntentoController::verificarIntento(0,0,$clave,$estudiante->id_est);
        /*$intento = new Intento();
        $intento->estudiante_id = $estudiante->id_est;
        $intento->clave_id = $clave->id;
        $intento->encuestado_id = null;
        $intento->numero_intento = 1;
        $intento->fecha_inicio_intento = Carbon::now('America/Denver')->format('Y-m-d H:i:s');
        $intento->save();*/
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
        $intento->numero_intento = 1;
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
