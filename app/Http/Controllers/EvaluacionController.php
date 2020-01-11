<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Grupo_Emparejamiento;
use App\Evaluacion;
use App\CicloMateria;
use App\CargaAcademica;
use App\Clave_Area_Pregunta_Estudiante;
use App\Turno;
use App\User;
use App\Clave_Area;
use App\Intento;
use App\Estudiante;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use DB;
use Mail;
use App\Exports\NotasExport;
use Maatwebsite\Excel\Facades\Excel;

use Exception;

class EvaluacionController extends Controller
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
     * Función que muestra el detalle de la evaluacion
     * @param 
     * @author Kike Menjivar
     */
    public function show($id){
    	$evaluacion = Evaluacion::findOrFail($id);
    	return view('evaluacion.detalleEvaluacion')->with(compact('evaluacion'));

    }

    /**
     * Función que despliega el formulario de crear evaluacion
     * recibe como parametro el id de la carga academica del docente
     * @param 
     * @author Edwin palacios
     */
    public function getCreate($id){
    	return view('evaluacion.createEvaluacion')->with(compact('id'));

    }

    /**
     * Función que recibe el request del formulario de crear evaluacion
     * recibe como parametro el id de la carga academica del docente
     * @param 
     * @author Edwin palacios
     */
    public function postCreate($id,Request $request){
        //dd($request->all());
        $rules =[
            
            'title' => ['required', 'string','min:5','max:191'],
            'description' => ['required'],
            'duration' => ['required'],
            'intentos' => ['required'],
            'paginacion' => ['required'],
        ];
        /* Mensaje de Reglas de Validación */
        $messages = [
            
            'title.required' => 'Debe de ingresar un título para la evaluación',
            'title.min' => 'El título debe contener como mínimo 5 caracteres',
            'description.required' => 'Debe de ingresar una descripción para la evaluacion',
            'duration.required' => 'Debe de indicar la duración del examen',
            'intentos.required' => 'Debe de indicar el numero de intentos de la evaluacion',
            'paginacion.required' => 'Debe de indicar la paginación de la evaluación',

        ];
        
        $this->validate($request,$rules,$messages);
        $evaluacion = new Evaluacion();
        $evaluacion->nombre_evaluacion= $request->input('title');
        $evaluacion->id_carga=$id;
        $evaluacion->duracion=$request->input('duration');
        $evaluacion->intentos=$request->input('intentos');
        $evaluacion->descripcion_evaluacion=$request->input('description');
        $evaluacion->preguntas_a_mostrar=$request->input('paginacion');
        $evaluacion->revision=0;

        if(isset($request->all()['revision']))
            $evaluacion->revision = 1;

        if(isset($request->all()['mostrar_nota']))
            $evaluacion->mostrar_nota = 1;

        $evaluacion->save();
        //return back()->with('notification','Se registró exitosamente');
        //return redirect()->action('EvaluacionController@listado', ['id' => $id]);
        return redirect(URL::signedRoute('listado_evaluacion', ['id' => $id]));


    }

    /**
     * Función que despliega el formulario de crear evaluacion
     * recibe como parametro el id de la evaluación a editar
     * @param 
     * @author Edwin palacios
     */
    public function getUpdate($id_eva){
        $evaluacion = Evaluacion::find($id_eva);
        return view('evaluacion.updateEvaluacion')->with(compact('evaluacion'));

    }

    /**
     * Función que recibe el request del formulario de editar evaluacion
     * recibe como parametro el id de la carga academica del docente
     * @param 
     * @author Edwin palacios
     */
    public function postUpdate($id_eva,Request $request){
        //dd($request->all());
        $rules =[
            
            'title' => ['required', 'string','min:5','max:191'],
            'description' => ['required'],
            'duration' => ['required'],
            'intentos' => ['required'],
            'paginacion' => ['required'],
        ];
        /* Mensaje de Reglas de Validación */
        $messages = [
            
            'title.required' => 'Debe de ingresar un título para la evaluación',
            'title.min' => 'El título debe contener como mínimo 5 caracteres',
            'description.required' => 'Debe de ingresar una descripción para la evaluacion',
            'duration.required' => 'Debe de indicar la duración del examen',
            'intentos.required' => 'Debe de indicar el numero de intentos de la evaluacion',
            'paginacion.required' => 'Debe de indicar la paginación de la evaluación',

        ];
        
        $this->validate($request,$rules,$messages);
        $evaluacion = Evaluacion::find($id_eva);;
        $evaluacion->nombre_evaluacion= $request->input('title');
        $evaluacion->duracion=$request->input('duration');
        $evaluacion->intentos=$request->input('intentos');
        $evaluacion->descripcion_evaluacion=$request->input('description');
        $evaluacion->preguntas_a_mostrar=$request->input('paginacion');
        $evaluacion->revision=0;
        $evaluacion->mostrar_nota = 0;

        if(isset($request->all()['revision']))
            $evaluacion->revision = 1;

        if(isset($request->all()['mostrar_nota']))
            $evaluacion->mostrar_nota = 1;

        $evaluacion->save();
        //return back()->with('notification','Se registró exitosamente');
        //return redirect()->action('EvaluacionController@listado', ['id' => $evaluacion->id_carga]);
        return redirect(URL::signedRoute('listado_evaluacion', ['id' => $evaluacion->id_carga]));


    }



    /**
     * listado de evaluaciones por docente
     * @param el id que recibe es la carga academica si es docente (role=1)
     *        el id que recibe es materia_ciclo si es admin (role=0)
     * @author Edwin palacios
     */
    public function listado($id){
        $fecha_hora_actual = Carbon::now('America/El_Salvador')->format('d/m/Y h:i A');
        $id_carga = $id;
        if(auth()->user()->IsAdmin){
            $cargas=  CargaAcademica::where('id_mat_ci',$id)->get();
            $evaluaciones = array();
            foreach ($cargas as $carga) {
                 $evas= Evaluacion::where('id_carga',$carga->id_carg_aca  )->get();
                 foreach ($evas as $eva) {
                    array_push($evaluaciones, $eva);
                 }
            }
        }elseif(auth()->user()->IsTeacher){
            $evaluaciones = Evaluacion::where('id_carga',$id)->where('habilitado',1)->get();
        }elseif(auth()->user()->IsStudent){
            $evaluaciones = array();
            //verificamos si exista la carga academica
            if(CargaAcademica::where('id_carg_aca',$id)->exists()){
                //obtenemos todas las cargas academicas de la materia, con el objetivo de presentar todas las evaluaciones de los docentes
                $carga_academica = CargaAcademica::where('id_carg_aca',$id)->first();
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
                                $turno->fecha_inicio_turno = $this->convertirFecha($turno->fecha_inicio_turno);
                                $turno->fecha_final_turno = $this->convertirFecha($turno->fecha_final_turno);
                                if($turno->visibilidad==1 &&
                                    $turno->fecha_final_turno > $fecha_hora_actual){
                                    $turnos_activos = true;
                                }
                            }
                            if($turnos_activos==true){
                                $evaluaciones[] = $evaluacion;
                            }
                        }
                    }
                }
                
            }
        }
        
    	return view('evaluacion.listaEvaluacion')->with(compact('evaluaciones','id_carga','fecha_hora_actual'));

    }

    /**
     * listado de evaluaciones deshabilitadas por docente
     * @param el id que recibe es la carga academica si es docente (role=1)
     *        el id que recibe es materia_ciclo si es admin (role=0)
     * @author Edwin palacios
     */

    public function reciclaje($id){
        $id_carga = $id;
        $evaluaciones = Evaluacion::where('id_carga',$id)->where('habilitado',0)->get();
        return view('evaluacion.recycleEvaluacion')->with(compact('evaluaciones','id_carga'));

    }

    /**
     * Deshabilita evaluaciones, con excepción de aquellas que cuentan con turnos que están en periodo de evaluacion
     * @param 
     * @author Edwin palacios
     */
    
    public function deshabilitarEvaluacion(Request $request){
        //dd($request->all());
        $id_evaluacion = $request->input('id_evaluacion');
        if($id_evaluacion){
            $si_deshabilita =true;
            $notification = 'exito';
            $mensaje = 'La evaluación ha sido deshabilitada exitosamente'; 
            $evaluacion = Evaluacion::find($id_evaluacion); 
            if($evaluacion->turnos){
                $fecha_actual = Carbon::now('America/El_Salvador')->format('Y-m-d H:i:s');
                foreach ($evaluacion->turnos as $turno) {
                    if($fecha_actual > $turno->fecha_inicio_turno && $fecha_actual < $turno->fecha_final_turno){
                        $notification = 'error';
                        $mensaje = 'La evaluacion no puede ser deshabilitada ya que posee uno o varios turnos en periodo de evaluación';
                        $si_deshabilita =false;
                    }
                }
            }
            if($si_deshabilita){
                $evaluacion->habilitado = 0;
                $evaluacion->save();
            }
        }else{
            $notification = 'error';
            $mensaje = 'La evaluacion no pudo ser deshabilitada, intente de nuevo';
        }

        return back()->with($notification, $mensaje);
    }

    /**
     * Función que permite habilitar evaluaciones
     * @param 
     * @author Edwin palacios
     */
    public function habilitar(Request $request){
        //dd($request->all());
        $id_evaluacion = $request->input('id_evaluacion');
        if($id_evaluacion){
            $notification = 'exito';
            $mensaje = 'La evaluación ha sido habilitada exitosamente'; 
            $evaluacion = Evaluacion::find($id_evaluacion); 
            $evaluacion->habilitado = 1;
            $evaluacion->save();
        }else{
            $notification = 'error';
            $mensaje = 'La evaluacion no pudo ser habilitada, intente de nuevo';
        }

        return back()->with($notification, $mensaje);
    }

    /**
     * Función que permite publicar evaluaciones,recibimos el id de los turnos que se desean publicar
     * @param 
     * @author Edwin palacios
     */

    public function publicar( Request $request){
        //dd($request->all());
        $turnos = $request->input('turnosnopublicos');
        $notification = "warning";
        $message = "";
        if($turnos){
            foreach($turnos as $turno){

                $estudiantes = array();
                $turno_publico = Turno::find($turno);
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
                                $message .= "Info: La sumatoria de pesos del turno => <strong> Inicio: </strong>" . $turno_publico->fecha_inicio_turno . " <strong> Final: </strong> " . $turno_publico->fecha_final_turno . " es de ". $sumatoria_de_pesos . ", menor al 100 requerido<br><br>";
                            }elseif($sumatoria_de_pesos>100){
                                $message .= "Info: La sumatoria de pesos del turno => <strong> Inicio: </strong>" . $turno_publico->fecha_inicio_turno . " <strong> Final: </strong> " . $turno_publico->fecha_final_turno . " es de ". $sumatoria_de_pesos . ", mayor al 100 requerido<br><br>";

                            }elseif($sumatoria_de_pesos==100){

                                /*CREACION DE CLAVES
                                *
                                */
                                $validacion = $this->validarBancoPreguntas($areas_de_clave);

                                if($validacion["todo_correcto"]){
                                    $claves = $this->generarClave($areas_de_clave,$turno);
                                }else{
                                    $message .= $validacion["message"] . " turno => <strong> Inicio: </strong>" . $turno_publico->fecha_inicio_turno . " <strong> Final: </strong> " . $turno_publico->fecha_final_turno;
                                    return back()->with($notification,$message);
                                }
                                
                                
                                /*
                                *
                                **/
                                $notification = "exito";
                                $message .= "Info: Publicación exitosa del turno => <strong> Inicio: </strong>" . $turno_publico->fecha_inicio_turno . " <strong> Final: </strong> " . $turno_publico->fecha_final_turno ."<br><br>";

                                $turno_publico->visibilidad = 1;
                                $turno_publico->fecha_inicio_turno= $this->restablecerFecha($turno_publico->fecha_inicio_turno);
                                $turno_publico->fecha_final_turno=  $this->restablecerFecha($turno_publico->fecha_final_turno);
                                $turno_publico->save();

                                try {

                                  $this->enviarCorreo($turno_publico);


                                } catch (Exception $e) {

                                    return back()->with($notification,$message);
                                }
                            }
                            

                        }else{
                            $message .= "Info: Debe agregar áreas de preguntas al turno => <strong> Inicio: </strong>" . $turno_publico->fecha_inicio_turno . " <strong> Final: </strong> " . $turno_publico->fecha_final_turno . "<br><br>";
                        }
                    }
                    
                }else{
                    $message .= "Info: no posee clave el turno => <strong>Inicio:</strong>" . $turno_publico->fecha_inicio_turno . " <strong>Final:</strong> " . $turno_publico->fecha_final_turno . "<br><br>";
                }  
            }
            
        }else{
            $notification = "warning";
            $message = "Info: no ha seleccionado ningún turno a publicar";
        }
        return back()->with($notification,$message); 
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
     * Funcion para validar el acceso a los intentos de evaluaciones.
     * @param 
     * @author Edwin Palacios
     */
    public function acceso(Request $request){
        //declaracion de variables
        $fecha_hora_actual = Carbon::now('America/El_Salvador')->format('Y-m-d H:i:s');
        $id_turno = $request->input('id_turno_acceso');
        $contrasenia = $request->input('contraseña');
        if($contrasenia){
            $estudiante = Estudiante::where('user_id', auth()->user()->id)->first();
            $turno_a_acceder =  Turno::find($id_turno);

            //validacion de fecha
            if(!($fecha_hora_actual >= $turno_a_acceder->fecha_inicio_turno && $turno_a_acceder->fecha_final_turno> $fecha_hora_actual )){
                $notification = "error";
                $message = "Error: La evaluacion no está disponible. " . $this->convertirFecha($fecha_hora_actual);
                return back()->with($notification,$message);
            } 
            
            $evaluacion = $turno_a_acceder->evaluacion;
            if($turno_a_acceder->CantIntentos <= 0){
                $notification = "error";
                $message = "Error: Ya ha realizado todos los intentos";
                return back()->with($notification,$message);
            }else{
                //Se valida si la contraseña es valida
                //devuelve false si son correctas
                if(!strcmp($contrasenia, $turno_a_acceder->contraseña)){
                    /*
                    return redirect()->action(
                        'IntentoController@iniciarEvaluacion', 
                        ['id_intento' => $turno_a_acceder->id]
                    );*/
                    return redirect('intento/'.$turno_a_acceder->id.'?page=1');
                }else{
                    $notification = "error";
                    $message = "Error: La contraseña no es valida";
                    return back()->with($notification,$message);
                }
            }
        }else{
            $notification = "error";
            $message = "Error: No ha ingresado la contraseña";
            return back()->with($notification,$message);
        }
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

            foreach ($estudiantes as $estudiante) {
                $usuario = User::find($estudiante->user_id);
                $this->emailSend($data,$usuario->email,"SIGEN: Nueva Evaluacíón");
            }
        }
    }

    /**
     * Funcion para enviar correo al publicar una evaluacion
     * @param $data: son los parametros para emplear la plantilla, se necesita: nombre de evaluacion, fechas de turno y materia
     * @param $correo: correo al que se enviará la notificación
     * @param $asunto: asunto del correo
     * @author Edwin Palacios
     */
    public function emailSend($data,$correo,$asunto){
        Mail::send('evaluacion.emailPublicar', $data , function($msj) use($asunto,$correo){
            $msj->from("sigen.fia.eisi@gmail.com","Sigen");
            $msj->subject($asunto);
            $msj->to($correo);
        });
        return redirect()->back();
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
     * Función para obtener el porcentaje de estudiante que aprobaron y reprobaron la evaluacion, asi como tambien
     * los que la realizaron y los que no la realizaron para mostrarlos en un gráfico
     * @param  int
     * @return json
     * @author Enrique Menjívar <mt16007@ues.edu.sv>
     */
    public function getPorcentajeAprovadosReprobados($evaluacion_id){
        $evaluacion = Evaluacion::findOrFail($evaluacion_id);

        //Obtener los intentos, es decir, los estudiantes que realizaron la evaluación
        $intentos = DB::table('turno as t')
                        ->where('t.evaluacion_id', $evaluacion_id)
                        ->join('clave as c', 'c.turno_id', '=', 't.id')
                        ->join('intento as i', 'i.clave_id', '=', 'c.id')
                        ->select('i.id', 'i.nota_intento')
                        ->get();

        //Obtener los intentos de los estudiantes cuya nota se mayor o igual que 6
        $aprobados_query = DB::table('turno as t')
                            ->where('t.evaluacion_id', $evaluacion_id)
                            ->join('clave as c', 'c.turno_id', '=', 't.id')
                            ->join('intento as i', 'i.clave_id', '=', 'c.id')
                            ->where('i.nota_intento', '>=', 6 )
                            ->select('i.id', 'i.nota_intento')
                            ->get();

         //Obtener el total de estudiantes que tenían derecho a realizar la evalución
         $total_estudiantes = DB::table('evaluacion as ev')
                                ->where('ev.id', $evaluacion_id)
                                ->join('carga_academica as ca', 'ev.id_carga', '=', 'ca.id_carg_aca')
                                ->join('detalle_insc_est as die', 'ca.id_carg_aca', '=', 'die.id_carg_aca')
                                ->join('estudiante as es', 'die.id_est', '=', 'es.id_est')
                                ->get();

        //Cantidades
        $total_evaluados = count($intentos);
        $aprobados = count($aprobados_query);
        $reprobados = $total_evaluados - $aprobados;
        $no_evaluados = count($total_estudiantes) - $total_evaluados;
        
        //Porcentajes
        $porcentaje_aprobados = ($aprobados/$total_evaluados)*100;
        $porcentaje_reprobados = ($reprobados/$total_evaluados)*100;
        $porcentaje_no_evaluados = ($no_evaluados/count($total_estudiantes))*100;
        $porcentaje_evaluados = ($total_evaluados/count($total_estudiantes))*100;
        
        //La variable $data es un json que contendrá todos los porcentajes calculados anteriormente con un precsion de 2 decimales
        $data = [
            'porcentaje_aprobados' => round($porcentaje_aprobados, 2),
            'porcentaje_reprobados' => round($porcentaje_reprobados, 2),
            'porcentaje_no_evaluados' => round($porcentaje_no_evaluados, 2),
            'porcentaje_evaluados' => round($porcentaje_evaluados, 2)
        ];

        return $data;
    }

    /**
     * Función para obtener la cantidad de estudiantes según el intervalos que se solicita y mostrarla en un gráfico
     * @param  int -> id de la evaluación
     * @param  int -> intervalo del histograma de notas
     * @return json
     * @author Enrique Menjívar <mt16007@ues.edu.sv>
     */
    public function getIntervalosNotas($evaluacion_id, $intervalo){
        $evaluacion = Evaluacion::findOrFail($evaluacion_id);
        $inc = 0; //Guardará un valor de incremento para consultar entre intevalos
        
        //Validacion para que no se intruduzca un valor diferente al mostrado en el combobox
        if($intervalo != 1 && $intervalo != 2 && $intervalo != 5){
            $intervalo = 1;
        }

        $lim_superior = $intervalo; //limite superior del intervalo
        $lim_inferior = 0;          //limite inferior del intervalo
        $max_y = 10;                //Logintud por defecto del eje y

        $cantidad = array(); //Guardará la cantidad por cada iteración según el intervalo indicado
        $etiquetas = array();//Giardará los números que se mostrarán en el eje X ya que varian según el intervalo

        array_push($etiquetas, 0);//Poner 0 como primer valor en el eje X

        for($i=0; $i<10; $i+=$intervalo){
            if($i>1){
                $inc = 0.0001;
            }
            //Obtienen los registros de intento cuya nota esta entre el intervalo indicado
            $notas = DB::table('turno as t')
                        ->where('t.evaluacion_id', $evaluacion_id)
                        ->join('clave as c', 'c.turno_id', '=', 't.id')
                        ->join('intento as i', 'i.clave_id', '=', 'c.id')
                        ->whereBetween('i.nota_intento', array($lim_inferior+$inc, $lim_superior) )
                        ->select('i.id', 'i.nota_intento')
                        ->get();

            if(count($notas) > 0){
                array_push($cantidad, count($notas)); 
            }else{
                array_push($cantidad, 0);
            }

            $lim_inferior = $lim_superior;
            $lim_superior += $intervalo;

            array_push($etiquetas, (int)$lim_inferior);
        }

        //Obtener el intervalos donde se concentran la mayor parte de notas y asignarselo al eje y
        foreach($cantidad as $c){
            if($c > $max_y){
                $max_y = $c;
            }

        }

        //La variable $data retornará un json con los valores calculados anteriormente
        $data = [
            'cantidad'  => $cantidad,
            'etiquetas' => $etiquetas,
            'max'       => 10,
            'max_x'     => 10-$intervalo,
            'max_y'     => $max_y
        ];

        return $data;
    }

    /**
     * Funcíon para mostrar los estadísticos de la evaluación correspondiente
     * @param  int -> id de la evaluación que se desean ver los estadísticos
     * @return view
     * @author Enrique Menjívar <mt16007@ues.edu.sv>
     */
    public function estadisticosEvaluacion($evaluacion_id){
        $message = '';
        $notification = '';
        $fecha_fin = '';
        $evaluacion = Evaluacion::findOrFail($evaluacion_id);
        $fecha_hora_actual = Carbon::now('America/El_Salvador')->format('Y-m-d H:i:s');
        $turnos = Turno::where('evaluacion_id', $evaluacion_id)->orderBy('fecha_final_turno', 'desc')->first();

        if($turnos){
            //validar que todos los turnos estén finalizados para ver los resultados
            if($turnos->fecha_final_turno > $fecha_hora_actual){
                $message = "El periodo para resolver la evaluación aún no ha terminado";
                $notification = 0;
            }else{
                //Cambiando el formato de la fecha
                $fecha_fin = $this->convertirFecha($turnos->fecha_final_turno);
                $message = $evaluacion->nombre_evaluacion;
                $notification = 1;
            }
        }else{
            $message = "Esta evaluación no tiene turnos";
            $notification = 0;
        }

        return view('evaluacion.estadisticosEvaluacion')->with(compact('evaluacion', 'notification', 'message', 'fecha_fin'));

    }


    //Parte para exportar notas en formato Excel

    public function exportarNotasExcel($evaluacion_id) 
    {
        $nombre_file = $this->getNombreEvaluacionFormato($evaluacion_id);
        //return (new NotasExport($evaluacion_id))->view();
        return Excel::download(new NotasExport($evaluacion_id), $nombre_file . '.xlsx');
    }

    //Parte para exportar notas en formato Pdf
    public function exportarNotasPdf($evaluacion_id) 
    {
        $nombre_file = $this->getNombreEvaluacionFormato($evaluacion_id);

        return Excel::download(new NotasExport($evaluacion_id), $nombre_file . '.pdf');
    }

    public function getNombreEvaluacionFormato($evaluacion_id){
        $evaluacion = Evaluacion::find($evaluacion_id);
        return 'notas_' . strval($this->formato_str($evaluacion->nombre_evaluacion));
    }

    public function formato_str($string){
        return str_replace(" ", "_", $string);
    }

}
