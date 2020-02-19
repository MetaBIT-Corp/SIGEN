<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Encuesta;
use App\Turno;
use App\Evaluacion;
use App\Clave;
use App\Estudiante;
use App\Docente;
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
use DateTime;
use Mail;

class ApiController extends Controller
{
    
	/*--------------------------Modelo Encuesta--------------------------*/
    //Funcion rotorna las encuestas de propósito general que se encuentra disponibles en formato JSON  
    public function encuestasDisponibles(){
        $fecha_hora_actual = Carbon::now('America/El_Salvador')->addMinutes(10)->format('Y-m-d H:i:s');
        $encuestas = Encuesta::whereDate('fecha_final_encuesta', '>', $fecha_hora_actual)->get();

        //dd($encuestas);
        $data = ['encuestas'=>$encuestas];
        return $data;
    }

    //Funcion que es llamada cuando finaliza el intento en el móvil
    public function finalizarIntentoMovil(Request $request){
        $respuesta = new Respuesta();
        $es_encuesta=$request->es_encuesta;
        $es_respuesta_corta = $request->es_rc; //Si la pregunta recibida es modalidad respuesta corta
        
        //cantidad total de preguntas que vienen desde el móvil
        $total_preguntas = $request->total_preguntas;

        //Obteniendo los valores del request y asignandolos a la tabla respuesta
        $respuesta->id_pregunta = $request->pregunta_id;        //pregunts
        
        //Verifica si la pregunta es respuesta corta
        if($es_respuesta_corta!=1){
            $respuesta->id_opcion = $request->opcion_id;            //opcion
        }

        $respuesta->id_intento = $request->intento_id;          //intento 
        $respuesta->texto_respuesta = $request->texto_respuesta;//texto escrito en caso sea respues corta

        //Guardar el objeto respuesta
        $respuesta->save();

        //Consulta la cantidad de respuestas que ha sido guardadas del intento correspondiente
        $num_actual = Respuesta::where('id_intento', $request->intento_id)->get();

        //Verifica si todas las respuestas que venian del movil ya se guardaron en la base de datos mysql
        if($total_preguntas == count($num_actual)){
        	$intento = Intento::find($request->intento_id);

        	//pergunta si es una encuesta
            if($es_encuesta==1){
        		//Actualizar los datos del intento correspondiente
	            $fecha_hora_actual = Carbon::now('America/El_Salvador')->format('Y-m-d H:i:s');
	            $intento->fecha_final_intento = $fecha_hora_actual;
	            $intento->save();
        	}else{
        		//Lama al método calcular nota
	            $nota = $intento->calcularNota($request->intento_id);

	            //Actualizar los datos del intento correspondiente
	            $fecha_hora_actual = Carbon::now('America/El_Salvador')->format('Y-m-d H:i:s');
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
        //recorremos turnos para dar formato a fecha d/m/Y h:i A
        foreach ($turnos as $turno) {
            $turno->fecha_inicio_turno = DateTime::createFromFormat('Y-m-d H:i:s',$turno->fecha_inicio_turno)->format('d/m/Y h:i A');
            $turno->fecha_final_turno = DateTime::createFromFormat('Y-m-d H:i:s',$turno->fecha_final_turno)->format('d/m/Y h:i A');
        }
        $data = ['turnos'=>$turnos];
        return $data;
    }

    /**
     * Metodo que devuelve las evaluaciones y turnos disponibles (MOVIL)..
     * @author Edwin Palacios
     * @param id_carga que corresponde al id de la carga academica del estudiante
     * @param role: que corresponde al role del usuario. 1: Docente 2:Estudiante
     * @return Json que contiene las evaluaciones y turnos disponibles.
     */ 
    public function evaluacionTurnosDisponibles($id_carga,$role){
    	//declaración de variables
        $fecha_hora_actual = Carbon::now('America/El_Salvador')->format('Y-m-d H:i:s');
        $evaluaciones = array();
        $turnosDisponibles = array();
        $comparaciones = array();
        $iteracion =0;

            //verificamos si existe la carga academica
            if(CargaAcademica::where('id_carg_aca',$id_carga)->exists()){
                //obtenemos todas las cargas academicas de la materia, con el objetivo de presentar todas las evaluaciones de los docentes en la materia
                $carga_academica = CargaAcademica::where('id_carg_aca',$id_carga)->first();

                // Si el usuario es estudiante
                if($role==2){
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
                                    
                                    $iteracion++;
                                    if($turno->visibilidad==1 && Carbon::parse($turno->fecha_final_turno)->gt(Carbon::parse($fecha_hora_actual))){
                                        
                                        $comparaciones[]= Carbon::parse($turno->fecha_final_turno)->gt(Carbon::parse($fecha_hora_actual));
                                        $turnos_activos = true;
                                        $turnosDisponibles[] = $turno;
                                    }
                                }
                                if($turnos_activos==true){
                                    $evaluaciones[] = $evaluacion;
                                }
                            }
                        }
                    }
                }
                //Si el usuario es docente
                if($role == 1){
                    $evaluaciones_all = Evaluacion::where('id_carga',$carga_academica->id_carg_aca)
                                    ->where('habilitado',1)
                                    ->get();
                    foreach ($evaluaciones_all as $evaluacion) {
                        $evaluaciones[] = $evaluacion;
                        if($evaluacion->turnos){
                            foreach ($evaluacion->turnos as $turno) {
                                $turnosDisponibles[] = $turno;
                            }
                        }
                    }
                }  


            } 
        $data = [
            'evaluaciones'=>$evaluaciones,
            'turnos' => $turnosDisponibles
        ];
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
        $docente = null;
        $user_autenticado = null;
        if( User::where('email',$email)->exists()){
            $user_no_autenticado = User::where('email',$email)->first();
            if(Hash::check($password, $user_no_autenticado->password)){
        	//if($user_no_autenticado->IsStudent && Hash::check($password, $user_no_autenticado->password)){
            	$user_autenticado = $user_no_autenticado;
                $user_autenticado->name = $password;
                if($user_autenticado->role == 1){
                    $docente = Docente::where('user_id',$user_autenticado->id)->first();
                }
                if($user_autenticado->role == 2){
                    $estudiante = Estudiante::where('user_id',$user_autenticado->id)->first();
                }
                
            }
        }
        
        $data = [
            'user'=>$user_autenticado,
            'estudiante'=>$estudiante,
            'docente'=>$docente
        ];
        return $data;
    }
    /*
     * Funcion para web service de materias que cursa un determinado estudiante
     * @param int $id_user ID del usuario del estudiante
     * @author Ricardo Estupinian
     */
    public function getMateriasEstudiante($id_user){
        $materias=MateriaController::materiasEstudiante($id_user);
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
        $intento->fecha_inicio_intento = Carbon::now('America/El_Salvador')->format('Y-m-d H:i:s');
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
            $clave_area_preguntas = Clave_Area_Pregunta_Estudiante::where('clave_area_id',$clave_area->id)->where('estudiante_id',$estudiante->id_est)->where('numero_intento',1)->get();
            
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
    
    public function getEncuesta($encuesta_id, $user_id){
        $encuesta_arr = array();
        //Primero obtenemos el objeto de Encuesta
        $encuesta = Encuesta::find($encuesta_id);
        $encuesta_arr['encuesta'] = $encuesta;
        
        //Buscamos si esta dirección MAC ya se encuentra registrada
        /*$encuestados = Encuestado::where('MAC',$mac)->get();
        $encuestado = null;
    
        if($encuestados->count())
            //En caso que la consulta anterior retorne objetos de Encuestado, obtenemos el primero
            $encuestado = $encuestados[0];
        else{
            //Si no hay ninguno registrado con esta MAC lo creamos
            $encuestado = new Encuestado();
            $encuestado->MAC = $mac;
            $encuestado->save();
        }*/
        
        //Buscamos al usuario que desea participar en la encuesta
        //$usuario = Usuario::find($user_id);
        //Ahora almacenamos en el Array al Usuario
        //$encuesta_arr['usuario'] = $usuario; //No es necesario, ya que este ya esta registrado en la BD del móvil
        
        //Procederemos a obtener la Clave, la cual se relaciona con la Encuesta directamente
        //Se asume por el momento que una Encuesta solamente poseera una Clave

        $clave = Clave::where('encuesta_id', $encuesta->id)->first();
        $encuesta_arr['clave'] = $clave;
        
        //Procederemos a crear el Intento
        $intento = new Intento(); 
        $intento->estudiante_id = null;
        $intento->user_id = $user_id;
        $intento->clave_id = $clave->id;
        $intento->numero_intento = 1;
        $intento->fecha_inicio_intento = Carbon::now('America/El_Salvador')->format('Y-m-d H:i:s');
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

    /**
     * Funcion para obtener las materias segun docente y el ciclo activo. Web Service.
     * @param int $id_user ID del usuario del estudiante
     * @author Ricardo Estupinian
     */
    public function getMateriasDocente($id_user){
        $materias = DB::table('cat_mat_materia')
            ->join('materia_ciclo', 'cat_mat_materia.id_cat_mat', '=', 'materia_ciclo.id_cat_mat')
            ->join('carga_academica', 'carga_academica.id_mat_ci', '=', 'materia_ciclo.id_mat_ci')
            ->join('pdg_dcn_docente', 'carga_academica.id_pdg_dcn', '=', 'pdg_dcn_docente.id_pdg_dcn')
            ->where('pdg_dcn_docente.user_id', '=', $id_user)
            ->join('ciclo', 'ciclo.id_ciclo', '=', 'materia_ciclo.id_ciclo')
            ->where('ciclo.estado', '=', 1)
            ->select('cat_mat_materia.*', 'materia_ciclo.*','carga_academica.*','pdg_dcn_docente.*','ciclo.*')
            ->get();    
        //dd($materias);                    
        return $materias;
    }

    /**
     * Funcion que rehutiliza la funcion de estadisticas de una evaluacion en especifico
     * @param int ID de la evaluacion
     * @return array Retorna un array asociativo con los datos requeridos.
     * @author Ricardo Estupinian
     */
    public function getEstadisticosEvaluacion($id_eva){
        //Validacion de disponibilidad de evaluacion
        $turnos = Turno::where('evaluacion_id', $id_eva)->orderBy('fecha_final_turno', 'desc')->first();
        $data=null;

        if($turnos){
            //Verificacion de fecha de turnos para mostrar datos
            $fecha_hora_actual = Carbon::now('America/El_Salvador')->format('Y-m-d H:i:s');
            if($turnos->fecha_final_turno > $fecha_hora_actual){
                $data= ["info"=>0];
                $notification = 0;
            }else{
                //Se envian los datos del grafico
                $data = EvaluacionController::getPorcentajeAprovadosReprobados($id_eva);
            }
        }else{
            $data = ["info"=>1];
        }
       
        return $data;
    }

    /*----------------------------------INICIO DE PUBLICACION DE TURNO -------------------------------------*/

    /**
     * Función que permite publicar evaluaciones,recibimos el id del turno que se desean publicar
     * @param 
     * @author Edwin palacios
     */

    public function publicarTurno($id_turno){
        //dd($request->all());
        $notification = "warning";
        $message = "";
        $estudiantes = array();
        $turno_publico = Turno::find($id_turno);
        $turno_publico->fecha_inicio_turno= $this->convertirFecha($turno_publico->fecha_inicio_turno);
        $turno_publico->fecha_final_turno= $this->convertirFecha($turno_publico->fecha_final_turno);
        
        if($turno_publico->claves){
            foreach ($turno_publico->claves as $clave) {
                if(Clave_Area::where('clave_id', $clave->id)->exists()){

                    $areas_de_clave = Clave_Area::where('clave_id', $clave->id)->get();
                    $sumatoria_de_pesos = 0;
                    foreach ($areas_de_clave as $area_de_clave) {
                        $sumatoria_de_pesos += $area_de_clave->peso;
                    }
                    if($sumatoria_de_pesos<100){
                        $message .= "Info: La sumatoria de pesos del turno es de ". $sumatoria_de_pesos . ", menor al 100 requerido";
                    }elseif($sumatoria_de_pesos>100){
                        $message .= "Info: La sumatoria de pesos del turno es de ". $sumatoria_de_pesos . ", mayor al 100 requerido<br><br>";

                    }elseif($sumatoria_de_pesos==100){

                        /*CREACION DE CLAVES
                        *
                        */
                        $validacion = $this->validarBancoPreguntas($areas_de_clave);

                        if($validacion["todo_correcto"]){
                            $claves = $this->generarClave($areas_de_clave,$id_turno);
                        }else{
                            $message .= $validacion["message"];
                            return ['resultado'=>$message];
                        }
                        
                        
                        /*
                        *
                        **/
                        $notification = "exito";
                        $message .= "Info: Publicación exitosa del turno";

                        $turno_publico->visibilidad = 1;
                        $turno_publico->fecha_inicio_turno= $this->restablecerFecha($turno_publico->fecha_inicio_turno);
                        $turno_publico->fecha_final_turno=  $this->restablecerFecha($turno_publico->fecha_final_turno);
                        $turno_publico->save();

                        try {
                          $this->enviarCorreo($turno_publico);
                        } catch (Exception $e) {
                            return ['resultado'=>$message];
                        }
                    }
                    

                }else{
                    $message .= "Info: Debe agregar áreas de preguntas al turno";
                }
            }
            
        }else{
            $message .= "Info: no posee clave el turno";
        }  
        return ['resultado'=>$message]; 
    }

     /**
     * Funcion para retornar los estudiantes de una materia.
     * @param id_turno
     * @author Edwin Palacios
     */
    public function getEstudiantesMateria($id_turno){
        //inicializamos variables
        $estudiantes = array();

        if(Turno::find($id_turno)->exists()){
            $turno = Turno::find($id_turno);
            $evaluacion = $turno->evaluacion;
            $carga_academica = $evaluacion->carga_academica;
            $materia_ciclo= $carga_academica->materiaCiclo;
            $estudiantes=DB::table('estudiante')
                ->join('detalle_insc_est','estudiante.id_est','=','detalle_insc_est.id_est')
                ->join('carga_academica','carga_academica.id_carg_aca','=','detalle_insc_est.id_carg_aca')
                ->join('materia_ciclo','materia_ciclo.id_mat_ci','=','carga_academica.id_mat_ci')
                ->where('materia_ciclo.id_mat_ci','=',$materia_ciclo->id_mat_ci)
                ->select('estudiante.*')->get();
        }
        return $estudiantes;
    }
    /**
     * Funcion para retornar el numero de intentos que posee la evaluacion.
     * @param id_turno
     * @author Edwin Palacios
     */
    public function getIntentosEvaluacion($id_turno){
        $intentos =0;
        if(Turno::find($id_turno)->exists()){
            $turno = Turno::find($id_turno);
            $evaluacion = $turno->evaluacion;
            $intentos= $evaluacion->intentos;
        }
        return $intentos;
    }

    /**
     * Funcion para convertir la fecha de formato letra a 2019-09-23 23:24:12.
     * @param array claves_area
     * @param int id_turno
     * @author Edwin Palacios
     */
    public function generarClave($claves_area,$id_turno){
        //estudiantes es un array de los estudiantes de la materia
        $estudiantes = $this->getEstudiantesMateria($id_turno);
        //cantidad de intentos permitidos
        $cant_intentos = $this->getIntentosEvaluacion($id_turno);
        
        foreach ($claves_area as $clave_area) {
            $area = $clave_area->area;
            $tipo_item = $area->tipo_item;
            //si son manuales es 0
            if($clave_area->aleatorio==0){
                //si son de emparejamiento (item id 3) u otra modalidad, el tratamiento es el mismo si es manual
                if($area->tipo_item_id == 3){
                    $clave_areas_preguntas = $clave_area->claves_areas_preguntas;
                }else{
                   $clave_areas_preguntas = $clave_area->claves_areas_preguntas->shuffle(); 
                }
                foreach ($clave_areas_preguntas as $clave_area_pregunta) {
                    foreach ($estudiantes as $estudiante) {
                       for( $i=1 ; $i<=$cant_intentos ; $i++){
                            Clave_Area_Pregunta_Estudiante::create([
                            'estudiante_id'=>$estudiante->id_est,
                            'clave_area_id'=>$clave_area->id,
                            'pregunta_id'=>$clave_area_pregunta->pregunta_id,
                            'numero_intento'=> $i
                            ]);
                        } 
                    }
                }
               
            //si son aleatorias (si es 1)  
            }elseif ($clave_area->aleatorio==1) {
                //si son de emparejamiento (item id 3) u otra modalidad, el tratamiento es el mismo
                foreach ($estudiantes as $estudiante) {
                    for( $i=1 ; $i<=$cant_intentos ; $i++){
                        $grupos_emparejamientos = $area->grupos_emparejamiento;
                        if($area->tipo_item_id == 3){
                            //si son de emparejamiento lo que se hace es obtener las preguntas aleatoriamente pero respetando el 
                            //orden de id, por tal motivo cuando el numero solitado es igual, simplemente se asigna.
                            if($clave_area->numero_preguntas >= $grupos_emparejamientos->count()){
                                $random_grupos_emparejamientos = $grupos_emparejamientos;
                            }else{
                                $random_grupos_emparejamientos = $grupos_emparejamientos->random($clave_area->numero_preguntas);
                                $random_grupos_emparejamientos = $random_grupos_emparejamientos->sortBy('id');
                            }
                        }else{
                            if($clave_area->numero_preguntas >= $grupos_emparejamientos->count()){
                                $random_grupos_emparejamientos = $grupos_emparejamientos->shuffle();
                            }else{
                                $random_grupos_emparejamientos = $grupos_emparejamientos->random($clave_area->numero_preguntas);
                            }
                        }
                        
                        foreach ( $random_grupos_emparejamientos as $grupo) {
                            foreach ($grupo->preguntas as $pregunta) {
                               
                                    Clave_Area_Pregunta_Estudiante::create([
                                    'estudiante_id'=>$estudiante->id_est,
                                    'clave_area_id'=>$clave_area->id,
                                    'pregunta_id'=>$pregunta->id,
                                    'numero_intento'=> $i
                                    ]);
                            } 
                        }
                    }
                }
            }
        }
    }

    /**
     * Funcion para validar que el banco de preguntas esté bien realizado, es decir, 
     * que cada grupo tenga su pregunta y cada pregunta su opcion, etc.
     * @param 
     * @author Edwin Palacios
     */
    public function validarBancoPreguntas($claves_areas){
        $todo_correcto = true;
        $message = "";
        //recorremos todas las clave_area
        foreach ($claves_areas as $clave_area) {
            $area = $clave_area->area;
            $tipo_item = $area->tipo_item;

            //si el area es manual
            if($clave_area->aleatorio==0){
                //se verifica que la clave area tenga clave area pregunta
                if($clave_area->claves_areas_preguntas->count()>0){
                    $claves_areas_preguntas = $clave_area->claves_areas_preguntas;
                    //se verifica que la clave area pregunta tenga pregunta
                    foreach ($claves_areas_preguntas as $clave_area_pregunta) {
                        if($clave_area_pregunta->pregunta->count()>0){
                            $pregunta = $clave_area_pregunta->pregunta;
                            //se verifica que la pregunta tengan opcion
                                if($pregunta->opciones->count()==0){
                                    $todo_correcto = false;                               
                                    $message = "Error: Hay preguntas sin opciones";
                                } 
                        }else{
                            $todo_correcto = false;                         
                            $message = "Error: No existen preguntas asignadas";
                        }
                    }
                }else{
                    $todo_correcto = false;
                    $message = "Error: Para la publicación debe agregar preguntas al área";
                }
            }
            //si la clave area es aleatoria
            else{
                //verificamos que el area tenga grupo de emparejamiento
                if($area->grupos_emparejamiento->count() >0){
                    foreach ($area->grupos_emparejamiento as $grupo_emparejamiento) {
                        //verificamos que grupo de emparejamiento tenga preguntas
                        if($grupo_emparejamiento->preguntas->count() >0){
                            foreach ($grupo_emparejamiento->preguntas as $pregunta) {
                                //verificamos que las preguntas tengan opciones
                                if($pregunta->opciones->count()==0){
                                    $todo_correcto = false;
                                    $message = "Error: Para la publicación todas las preguntas deben contener opciones";
                                }
                            }
                        }else{
                            $todo_correcto = false;
                            $message = "Error: Para la publicación todos los grupos de emparejamiento debe contener preguntas";
                        }
                    }
                }else{
                    $todo_correcto = false;
                    $message = "Error: Para la publicación las áreas deben tener grupo de emparejamiento";
                }
            } 
        }

        $validacion = ['todo_correcto'=>$todo_correcto, 'message' => $message];
        return $validacion;
    }

    /**
     * Funcion para convertir la fecha de formato 2019-09-23 23:24:12 a letra
     * @param fecha
     * @author Edwin Palacios
     */
    public function convertirFecha($fecha){
        $new_fecha = DateTime::createFromFormat('Y-m-d H:i:s',$fecha)->format('d/m/Y h:i A');
        return $new_fecha;
    }

    /**
     * Funcion para convertir la fecha de formato letra a formato como guarda en la base de datos 2019-09-23 23:24:12.
     * @param fecha
     * @author Edwin Palacios
     */
    public function restablecerFecha($fecha){
        $new_fecha = DateTime::createFromFormat('d/m/Y h:i A',$fecha)->format('Y-m-d H:i:s');
        return $new_fecha;
    }

    /**
     * Funcion para enviar correo de notificación de una nueva evaluación a todos los 
     * estudiantes inscritos en una evaluación
     * @param $turno: el turno a publicar
     * @author Edwin Palacios
     */
    public function enviarCorreo($turno){
        $estudiantes = $this->getEstudiantesMateria($turno->id);
        if($estudiantes->count()>0){
            $evaluacion = $turno->evaluacion;
            $periodo = "Periodo de disponibilidad: Desde " . 
                        $this->convertirFecha($turno->fecha_inicio_turno) . 
                        " Hasta: " . 
                        $this->convertirFecha($turno->fecha_final_turno);
            $data = [
                "periodo" => $periodo,
                "descripcion" => $evaluacion->descripcion_evaluacion,
                "titulo" => $evaluacion->nombre_evaluacion
            ];

            $asunto = "SIGEN: Nueva Evaluacíón";

            foreach ($estudiantes as $estudiante) {
                $usuario = User::find($estudiante->user_id);
                $correo = $usuario->email;
                Mail::send(
                    'evaluacion.emailPublicar', 
                    $data, 
                    function($msj) use($asunto,$correo){
                        $msj->from("sigen.fia.eisi@gmail.com","Sigen");
                        $msj->subject($asunto);
                        $msj->to($correo);
                    });
            }
        }
    }
    /*------------------------------ FIN DE PUBLICAR EVALUACION -----------------------------*/
}
