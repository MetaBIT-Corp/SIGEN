<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Estudiante;
use App\DetalleInscEst;
use App\CicloMateria;
use App\CargaAcademica;
use App\Ciclo;
use App\Materia;
use App\Evaluacion;
use App\Intento;
use App\User;
use Carbon\Carbon;
use DB;
use DateTime;
use DateInterval;
use Illuminate\Validation\Rule;

class EstudianteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function __construct()
     {
	$this->middleware('auth');
     }
    //recibo materia ciclo
    public function index($id)
    {
        
        $estudiantes=DB::table('estudiante')
        ->join('detalle_insc_est','estudiante.id_est','=','detalle_insc_est.id_est')
        ->join('carga_academica','carga_academica.id_carg_aca','=','detalle_insc_est.id_carg_aca')
        ->join('materia_ciclo','materia_ciclo.id_mat_ci','=','carga_academica.id_mat_ci')
        ->where('materia_ciclo.id_mat_ci','=',$id)
        ->select('estudiante.*')->get();

        $id_mat_ci = $id;
        $materia = Materia::where('id_cat_mat',CicloMateria::where('id_mat_ci',$id)->first()->id_cat_mat)->first();
        
        return view("estudiante/listadoEstudiante",compact("estudiantes", "id_mat_ci","materia"));
    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $id_mat_ci)
    {
        $estudiante = Estudiante::where('id_est',$id)->first();
        
        $detalles = DetalleInscEst::where('id_est',$id)->get();
        
        $mat_ci_valido = false;
        
        if(count(CicloMateria::where('id_mat_ci', $id_mat_ci)->get())){
            
            $mat_ci_valido = true; 
            
            $materia_consulta = Materia::where('id_cat_mat',CicloMateria::where('id_mat_ci', $id_mat_ci)->first()->id_cat_mat)->first();
        
            $ciclo = Ciclo::where('id_ciclo',CicloMateria::where('id_mat_ci', $id_mat_ci)->first()->id_ciclo)->first();

            $materia_consulta_codido = $materia_consulta->codigo_mat;

            $materias_cursando = array();

            $consulta_valida=false;

            foreach($detalles as $detalle){

                $materia_ciclo = CicloMateria::where('id_mat_ci',CargaAcademica::where('id_carg_aca',$detalle->id_carg_aca)->first()->id_mat_ci)->first();

                if(Ciclo::where('id_ciclo',$materia_ciclo->id_ciclo)->first()->estado){

                    $materia = Materia::where('id_cat_mat',$materia_ciclo->id_cat_mat)->first();

                    if($materia->id_cat_mat==$materia_consulta->id_cat_mat)$consulta_valida=true;

                    $materias_cursando[] = Materia::where('id_cat_mat',$materia_ciclo->id_cat_mat)->first();

                }

            }
            
            return view('estudiante.detalleEstudiante',compact('estudiante','materias_cursando','consulta_valida','materia_consulta_codido','ciclo','mat_ci_valido', 'id_mat_ci'));
            
        }
        
        
        
        return view('estudiante.detalleEstudiante',compact('mat_ci_valido'));
    }

    /**
     * Funcion para convertir la fecha de formato 2019-09-23 23:24:12 a letra
     * @param fecha
     * @author Edwin Palacios
     */
    public function convertirFecha($fecha){
        if($fecha){
            $new_fecha = DateTime::createFromFormat('Y-m-d H:i:s',$fecha)->format('d/m/Y h:i A');
            return $new_fecha;
        }else{
            return ' - ';
        }
    }

    /**
     * Función para mostrar los estudiantes que se encuentran en la evalución 
     * @param  int -> id de la evalución que se desea consultar la información
     * @return view
     * @author Enrique Menjívar <mt16007@ues.edu.sv>
     */
    public function estudiantesEnEvaluacion($evaluacion_id){

        $evaluacion = Evaluacion::findOrFail($evaluacion_id);
        
        //Llamada al metodo evaluacionFinalizada($evaluacion_id);
        $evaluacion_finalizada = $this->evaluacionFinalizada($evaluacion_id);

        //Obtener los estudiantes que tienen derecho a la evalución
        $estudiantes = DB::table('evaluacion as ev')
                            ->where('ev.id', $evaluacion_id)
                            ->join('carga_academica as ca', 'ev.id_carga', '=', 'ca.id_carg_aca')
                            ->join('detalle_insc_est as die', 'ca.id_carg_aca', '=', 'die.id_carg_aca')
                            ->join('estudiante as es', 'die.id_est', '=', 'es.id_est')
                            ->select('es.id_est', 'es.carnet', 'es.nombre')
                            ->orderBy('es.carnet', 'asc')
                            ->get();

        //Agregando las columnas necesarias al array $estudiantes
        $estudiantes->pluck('inicio');
        $estudiantes->pluck('final');
        $estudiantes->pluck('nota');
        $estudiantes->pluck('estado'); // 0: No iniciado; 1: Iniciado; 2: Finalizado
        $estudiantes->pluck('turno');
        $estudiantes->pluck('id_intento'); 
        $estudiantes->pluck('revision_estudiante');

        //Obtner información para las columnas recién agregadas para cada estudiante
        foreach($estudiantes as $estudiante){

            //Consulta para verficar si existe un intento de la evaluación, es decir, verificar que el estudiante ya haya iniciado la evaluación
            $intento = DB::table('turno as t')
                            ->where('t.evaluacion_id', $evaluacion_id)
                            ->join('clave as c', 'c.turno_id', '=', 't.id')
                            ->join('intento as i', 'i.clave_id', '=', 'c.id')
                            ->where('i.estudiante_id', $estudiante->id_est)
                            ->select('i.fecha_inicio_intento', 'i.fecha_final_intento', 'i.nota_intento', 'i.id', 'i.revision_estudiante')
                            ->get();

            //Si ya la incicio
            if(count($intento) > 0){
                $estudiante->inicio = $this->convertirFecha($intento[0]->fecha_inicio_intento);
                $estudiante->final = $this->convertirFecha($intento[0]->fecha_final_intento);
                $estudiante->turno = 'Turno ' . $this->obtenerTurno($intento[0]->id);
                $estudiante->nota = $intento[0]->nota_intento;
                $estudiante->id_intento = $intento[0]->id;
                $estudiante->revision_estudiante = $intento[0]->revision_estudiante;
                
                //Verficar si ya terminó la evaluación
                if($intento[0]->fecha_inicio_intento && $intento[0]->fecha_final_intento){
                    $estudiante->estado = 2; //Asignación de estado Finalizado
                }else{ 
                    $estudiante->estado = 1; //Asignación de estado Iniciado
                }
            //Si no la ha iniciado
            }else{
                $estudiante->inicio = ' - ';
                $estudiante->final =  ' - ';
                $estudiante->nota =  ' - ';
                $estudiante->estado = 0;    //Asignación de estado No iniciado
                $estudiante->turno = ' - ';
                $estudiante->id_intento = 0;
                $estudiante->revision_estudiante = 0;
            }

            $this->finalizarIntentos($estudiante,$evaluacion);

        }

        $promedio = $this->getPromedioEvaluacion($evaluacion_id);

        return view('estudiante.estudiantesEnEvaluacion')->with(compact('estudiantes','evaluacion_finalizada','evaluacion_id', 'evaluacion', 'promedio'));
    }

    public function evaluacionFinalizada($evaluacion_id){
        $turnos = Evaluacion::find($evaluacion_id)->turnos;
        $finalizado = 1;
        $fecha_hora_actual = Carbon::now('America/El_Salvador')->format('Y-m-d H:i:s');

        foreach ($turnos as $turno) {
            if(!Carbon::parse($fecha_hora_actual)->gt(Carbon::parse($turno->fecha_final_turno)))
                return 0;
        }

        return $finalizado;
    }


    /**
     * Función para obtener el turno en el que inicio el estudieante
     * @param  int $intento_id
     * @return int -> numero de turno
     */
    public function obtenerTurno($intento_id){
        $numero = 1;                                 //Se inicializa la variable numero con 1
        $intento = Intento::findOrFail($intento_id); //Se obitiene el objeto intento
        $turno = $intento->clave->turno;            //Se obtiene el turno del desde el que se inicio el intneto
        $evaluacion = $turno->evaluacion;           //Se obtiene la evaluación del turno
        $turnos_evaluacion = $evaluacion->turnos;   //Se obtienen todos los turnos de la evalución

        //Obener el numero del turno que inicio el estudiante
        foreach ($turnos_evaluacion as $t) {
            if($t->id == $turno->id){
                break;
            }

            $numero++;
        }

        return $numero;
    }

     /**
     * Método para verificar que todos los intentos a los que se les ha terminado el tiempo valido de evaluación
     * estén finalizados, en caso de no encontrarse finalizados, se invoca al método de finalización de Intento.
     * @param  Entidad 'Estudiante' generada en método de 'EstudiantesEnEvaluación' / Evaluacion $evaluacion
     * @return void
     * @author Carlos René Martínez Rivera
     */
     public function finalizarIntentos($estudiante,$evaluacion){

        $fecha_inicio_estudiante = Intento::select('fecha_inicio_intento')->where('id',$estudiante->id_intento)->first();

        if($fecha_inicio_estudiante){

            $minutos_agregar = $evaluacion->duracion;
            $fecha_fin_estudiante = new DateTime($fecha_inicio_estudiante['fecha_inicio_intento']);
            $fecha_fin_estudiante->add(new DateInterval('PT' . $minutos_agregar . 'M'));
            $fecha_fin_formateada = $fecha_fin_estudiante->format('Y-m-d H:i:s');

            if($fecha_fin_formateada<=(Carbon::now('America/El_Salvador')->format('Y-m-d H:i:s'))){
               
                app('App\Http\Controllers\IntentoController')->finalizarIntentoWeb($estudiante->id_intento);
            }
        
        }

     }

     public function indexGlobal(){
         $estudiantes = Estudiante::all();
         $last_update = Estudiante::max('updated_at');

         return view('estudiante.index', compact('estudiantes', 'last_update'));
     }

     public function destroy(Request $request){

        $cant_die = DetalleInscEst::where('id_est', $request['estudiante_id'])->count();

        if($cant_die > 0)
            return back()->with('notification-type','warning')->with('notification-message','El Estudiante no puede ser eliminado, debido a que posee inscripciones en distintas cargas académicas.');
        
        Estudiante::where('id_est', $request['estudiante_id'])->delete();

        return back()->with('notification-type','success')->with('notification-message','El Estudiante se ha eliminado con éxito.');
     }

     /**
      * Función para obtener la nota promedio de la evaluación
      * @author Enrique Menjívar <mt16007@ues.edu.sv>
      * @param  $evaluacion_id : id de la evlauación
      * @return promedio : promedio de la evaluación
      */
     public function getPromedioEvaluacion($evaluacion_id){

        $nota = 0;
        
        //Consulta para verficar si existe un intento de la evaluación, es decir, verificar que el estudiante ya haya iniciado la evaluación
        $intentos_finalizados = DB::table('turno as t')
                            ->where('t.evaluacion_id', $evaluacion_id)
                            ->join('clave as c', 'c.turno_id', '=', 't.id')
                            ->join('intento as i', 'i.clave_id', '=', 'c.id')
                            ->whereNotNull('i.nota_intento')
                            ->select('i.nota_intento')
                            ->get();

        //Se suman las notas de los intentos finalizados de la evaluacion correspondiente
        foreach ($intentos_finalizados as $intento) {
            $nota += $intento->nota_intento;
        }

        $cantidad = count($intentos_finalizados);

        return round($nota/$cantidad, 2);
     }

     public function downloadExcel(){
        return Storage::download("plantillaExcel/ImportarEstudiantes.xlsx","Listado_Estudiantes_SIGEN.xlsx");
     }

     /**
     * Función que despliega el formulario de crear estudiante
     * @param 
     * @author Edwin palacios
     */
    public function getCreate(){
        return view('estudiante.createEstudiante');

    }

    /**
     * Función que recibe el request del formulario de crear estudiante
     * @param 
     * @author Edwin palacios
     */
    public function postCreate(Request $request){
<<<<<<< HEAD
        //dd($request->all());
        $rules =[
            
            'nombre' => ['required', 'string','min:5','max:191'],
            'carnet' => ['required', 'unique:estudiante,carnet'],
            'anio_ingreso' => ['required'],
            'email' => ['required', 'unique:users,email'],
        ];
        /* Mensaje de Reglas de Validación */
        $messages = [
            
            'nombre.required' => 'Debe de ingresar el nombre del estudiante',
            'nombre.min' => 'El nombre debe contener como mínimo 5 caracteres',
            'nombre.max' => 'El nombre debe contener como máximo 191 caracteres',
            'carnet.required' => 'Debe de indicar el carnet del estudiante',
            'carnet.unique' => 'El carnet ya existe. Por favor ingreso uno nuevo',
            'email.required' => 'Debe de indicar el email del estudiante',
            'email.unique' => 'El email ya existe. Por favor ingreso uno nuevo',
            'anio_ingreso.required' => 'Debe de indicar el año de ingreso',
        ];
        $this->validate($request,$rules,$messages);

        $pass = str_random(10);

        //Se crea usuario del docente
        $user = new User();
        $user->name = $request->input('nombre');
        $user->email = $request ->input('email');
        $user->password = bcrypt($pass);
        $user->role = 2;
        $user->save();

        //Se crea el estudiante 
        $estudiante = new Estudiante();
        $estudiante->nombre = $request->input('nombre');
        $estudiante->carnet = $request->input('carnet');
        $estudiante->anio_ingreso = $request->input('anio_ingreso');
        $estudiante->user_id = $user->id;

        if(isset($request->all()['activo']))
            $estudiante->activo = 1;

        $estudiante->save();
        return redirect()->route("estudiantes_index")->with("notification-message", 'Estudiante registrado exitosamente')
                                                  ->with("notification-type", 'success');
=======
        dd($request->all());
        return redirect('estudiantes_index');
>>>>>>> 2d7665ea9692b2f2556b060b5015afc080e9ab19
    }

    /**
     * Función que despliega el formulario de editar estudiante
     * @param 
     * @author Edwin palacios
     */
<<<<<<< HEAD
    public function getUpdate($estudiante_id){
        $estudiante = Estudiante::where('id_est', '=', $estudiante_id)->first();
        $user = User::find($estudiante->user_id);
        $email = $user->email;
        return view('estudiante.updateEstudiante')->with(compact('estudiante', 'email'));
=======
    public function getUpdate(){
        return view('estudiante.updateEstudiante');
>>>>>>> 2d7665ea9692b2f2556b060b5015afc080e9ab19
    }

    /**
     * Función que recibe el request del formulario de editar estudiante
     * @param 
     * @author Edwin palacios
     */
    public function postUpdate(Request $request){
<<<<<<< HEAD
        //dd($request->all());
        $rules =[
            
            'nombre' => ['required', 'string','min:5','max:191'],
            'carnet' => ['required', Rule::unique('estudiante', 'carnet')
                                    ->ignore($request->input('id_est'), 'id_est')],
            'anio_ingreso' => ['required'],
            'email' => ['required', Rule::unique('users', 'email')
                                    ->ignore($request->input('user_id'))],
        ];
        /* Mensaje de Reglas de Validación */
        $messages = [
            
            'nombre.required' => 'Debe de ingresar el nombre del estudiante',
            'nombre.min' => 'El nombre debe contener como mínimo 5 caracteres',
            'nombre.max' => 'El nombre debe contener como máximo 191 caracteres',
            'carnet.required' => 'Debe de indicar el carnet del estudiante',
            'carnet.unique' => 'El carnet ya existe. Por favor ingreso uno nuevo',
            'email.required' => 'Debe de indicar el email del estudiante',
            'email.unique' => 'El email ya existe. Por favor ingreso uno nuevo',
            'anio_ingreso.required' => 'Debe de indicar el año de ingreso',
        ];
        $this->validate($request,$rules,$messages);
        
        //Se obtiene usuario del estudiante
        $user = User::find($request->input('user_id'));
        $user->name = $request->input('nombre');
        $user->email = $request ->input('email');
        $user->save();

        //Se crea el estudiante 
        $estudiante = Estudiante::where('id_est', '=', $request->input('id_est'))->first();
        $estudiante->nombre = $request->input('nombre');
        $estudiante->carnet = $request->input('carnet');
        $estudiante->anio_ingreso = $request->input('anio_ingreso');

        if(isset($request->all()['activo'])){
            $estudiante->activo = 1;
        }else{
            $estudiante->activo = 0;
        }

        $estudiante->save();
        return redirect()->route("estudiantes_index")->with("notification-message", 'Datos del estudiante actualizados exitosamente')
                                                  ->with("notification-type", 'success');


=======
        dd($request->all());
        return redirect('estudiantes_index');
>>>>>>> 2d7665ea9692b2f2556b060b5015afc080e9ab19
    }
    /*
      * Cambia el estado del usuario del estudiante, si está bloqueado lo habilita y viceversa
      * @author Enrique Menjívar <mt16007@ues.edu.sv>
      * @param  Request $request Datos enviados desde el frontend
      */
     public function changeStateEstudiante(Request $request){
        $id_est = $request->input('est_id');
        $estudiante = Estudiante::where('id_est', $id_est)->first();

        $user = User::findOrFail($estudiante->user_id);

        if($user->enabled == 1){
            $user->enabled = 0;
            $message = 'El Estudiante con carnet <em><b>' . $estudiante->carnet  . '</b></em> fue bloqueado con éxito';
        }else{
            $user->enabled = 1;
            $message = 'El Estudiante con carnet <em><b>' . $estudiante->carnet  . '</b></em> fue desbloqueado con éxito';
        }

        $user->save();

        return back()->with('message', $message);
     }

}