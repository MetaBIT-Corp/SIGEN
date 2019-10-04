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
    
    //muestra el detalle de la evaluacion
    public function show($id){
    	$evaluacion = Evaluacion::findOrFail($id);
    	return view('evaluacion.detalleEvaluacion')->with(compact('evaluacion'));

    }

    //crear Evaluacion
    public function getCreate($id){
    	return view('evaluacion.createEvaluacion')->with(compact('id'));

    }

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
        $evaluacion->save();
        //return back()->with('notification','Se registró exitosamente');
        //return redirect()->action('EvaluacionController@listado', ['id' => $id]);
        return redirect(URL::signedRoute('listado_evaluacion', ['id' => $id]));


    }

    //en update se recibe como parametro el id de la evaluación a editar
    public function getUpdate($id_eva){
        $evaluacion = Evaluacion::find($id_eva);
        return view('evaluacion.updateEvaluacion')->with(compact('evaluacion'));

    }

    //en update se recibe como parametro el id de la evaluación a editar
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

        if(isset($request->all()['revision']))
            $evaluacion->revision = 1;
        $evaluacion->save();
        //return back()->with('notification','Se registró exitosamente');
        //return redirect()->action('EvaluacionController@listado', ['id' => $evaluacion->id_carga]);
        return redirect(URL::signedRoute('listado_evaluacion', ['id' => $evaluacion->id_carga]));


    }


    /*listado de evaluaciones por docente
    el id que recibe es la carga academica si es docente (role=1)
    el id que recibe es materia_ciclo si es admin (role=0)*/

    public function listado($id){
        $fecha_hora_actual = Carbon::now('America/Denver')->format('Y-m-d H:i:s');
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

    /*listado de evaluaciones por docente
    el id que recibe es la carga academica si es docente (role=1)
    el id que recibe es materia_ciclo si es admin (role=0)*/

    public function reciclaje($id){
        $id_carga = $id;
        $evaluaciones = Evaluacion::where('id_carga',$id)->where('habilitado',0)->get();
        return view('evaluacion.recycleEvaluacion')->with(compact('evaluaciones','id_carga'));

    }

    //Deshabilita evaluaciones, con excepción de aquellas que cuentan con turnos que están en periodo de evaluacion
    public function deshabilitarEvaluacion(Request $request){
        //dd($request->all());
        $id_evaluacion = $request->input('id_evaluacion');
        if($id_evaluacion){
            $si_deshabilita =true;
            $notification = 'exito';
            $mensaje = 'La evaluación ha sido deshabilitada exitosamente'; 
            $evaluacion = Evaluacion::find($id_evaluacion); 
            if($evaluacion->turnos){
                $fecha_actual = Carbon::now('America/Denver')->format('Y-m-d H:i:s');
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

    //habilita evaluaciones
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

    //recibimos el id de los turnos que se desean publicar
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
                                //verificamos si la clave_area es manual. es decir que no sean aleatorias
                                if(!($area_de_clave->aleatorio)){
                                    $preguntas_de_clave_area_pregunta = $area_de_clave->claves_areas_preguntas;
                                    if($preguntas_de_clave_area_pregunta->count() == 0){
                                        $message .= "Info: El turno => <strong> Inicio: </strong>" . $turno_publico->fecha_inicio_turno . " <strong> Final: </strong> " . $turno_publico->fecha_final_turno . " posee area(s) que no han sido asignadas preguntas, por favor verificar.";
                                        return back()->with($notification,$message);
                                    }
                                }   
                            }
                            if($sumatoria_de_pesos<100){
                                $message .= "Info: La sumatoria de pesos del turno => <strong> Inicio: </strong>" . $turno_publico->fecha_inicio_turno . " <strong> Final: </strong> " . $turno_publico->fecha_final_turno . " es de ". $sumatoria_de_pesos . ", menor al 100 requerido<br><br>";
                            }elseif($sumatoria_de_pesos>100){
                                $message .= "Info: La sumatoria de pesos del turno => <strong> Inicio: </strong>" . $turno_publico->fecha_inicio_turno . " <strong> Final: </strong> " . $turno_publico->fecha_final_turno . " es de ". $sumatoria_de_pesos . ", mayor al 100 requerido<br><br>";

                            }elseif($sumatoria_de_pesos==100){

                                /*CREACION DE CLAVES
                                *
                                */

                                $claves = $this->generarClave($areas_de_clave,$turno);
                                
                                /*
                                *
                                **/
                                $notification = "exito";
                                $message .= "Info: Publicación exitosa del turno => <strong> Inicio: </strong>" . $turno_publico->fecha_inicio_turno . " <strong> Final: </strong> " . $turno_publico->fecha_final_turno ."<br><br>";

                                $turno_publico->visibilidad = 1;
                                $turno_publico->fecha_inicio_turno= $this->restablecerFecha($turno_publico->fecha_inicio_turno);
                                $turno_publico->fecha_final_turno=  $this->restablecerFecha($turno_publico->fecha_final_turno);
                                $turno_publico->save();
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
     * Funcion para validar el acceso a los intentos de evaluaciones.
     * @param 
     * @author Edwin Palacios
     */
    public function acceso(Request $request){
        //declaracion de variables
        $fecha_hora_actual = Carbon::now('America/Denver')->format('Y-m-d H:i:s');
        $id_turno = $request->input('id_turno_acceso');
        $contrasenia = $request->input('contraseña');
        if($contrasenia){
            $estudiante = Estudiante::where('user_id', auth()->user()->id)->first();
            $turno_a_acceder =  Turno::find($id_turno);

            //validacion de fecha
            if(!($fecha_hora_actual >= $turno_a_acceder->fecha_inicio_turno && $turno_a_acceder->fecha_final_turno> $fecha_hora_actual )){
                $notification = "error";
                $message = "Error: La evaluacion no está disponible. " . $fecha_hora_actual;
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
        //recorremos cada estudiante
        foreach ($claves_area as $clave_area) {
            $this->obtenerPreguntasClaveArea($clave_area,$estudiantes,$cant_intentos);
        }
    }

    /**
     * Funcion para obtener preguntas de un clave area
     * @param f
     * @author Edwin Palacios
     */
    public function obtenerPreguntasClaveArea($clave_area,$estudiantes,$cant_intentos){
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
           
        //si son aleatorias es 1  
        }elseif ($clave_area->aleatorio==1) {
            //si son de emparejamiento (item id 3) u otra modalidad, el tratamiento es el mismo
            foreach ($estudiantes as $estudiante) {
                for( $i=1 ; $i<=$cant_intentos ; $i++){
                    /*
                    //si no es de emparejamiento se barajean las preguntas dentro del grupo emparejamiento
                    if($tipo_item->id != 3){
                            $grupo = $area->grupos_emparejamiento->first(); 
                            $preguntas_all = $grupo->preguntas;
                            if($clave_area->numero_preguntas >= $preguntas_all->count()){
                                 $random_preguntas = $preguntas_all->shuffle();
                            }else{
                                $random_preguntas = $preguntas_all->random($clave_area->numero_preguntas);
                            }
                            
                            foreach ($random_preguntas as $pregunta) {
                                    Clave_Area_Pregunta_Estudiante::create([
                                    'estudiante_id'=>$estudiante->id_est,
                                    'clave_area_id'=>$clave_area->id,
                                    'pregunta_id'=>$pregunta->id,
                                    'numero_intento'=> $i
                                    ]);
                            }

                    }
                    //si es de emparejamiento se barajean los grupos de emparejamiento
                    else{*/
                        $grupos_emparejamientos = $area->grupos_emparejamiento;
                        if($area->tipo_item_id == 3){

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
                    //}
                    
                }
            }
         
        }
    }

    /**
     * Funcion para convertir la fecha de formato 2019-09-23 23:24:12 a letra
     * @param fecha
     * @author Edwin Palacios
     */
    public function convertirFecha($fecha){
        $new_fecha = DateTime::createFromFormat('Y-m-d H:i:s',$fecha)->format('l jS \\of F Y h:i A');
        return $new_fecha;
    }

    /**
     * Funcion para convertir la fecha de formato letra a 2019-09-23 23:24:12.
     * @param fecha
     * @author Edwin Palacios
     */
    public function restablecerFecha($fecha){
        $new_fecha = DateTime::createFromFormat('l jS \\of F Y h:i A',$fecha)->format('Y-m-d H:i:s');
        return $new_fecha;
    }

    
    public function random(){
        $turnos = Turno::All();
        $turno_random = $turnos->random(4);
        return $turno_random->first();
    }
}
