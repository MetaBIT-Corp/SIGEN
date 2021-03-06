<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Estudiante;
use App\DetalleInscEst;
use App\CicloMateria;
use App\CargaAcademica;
use App\Ciclo;
use App\Materia;
use App\Evaluacion;
use App\Intento;
use Carbon\Carbon;
use DB;
use DateTime;
use DateInterval;

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

        return view('estudiante.estudiantesEnEvaluacion')->with(compact('estudiantes','evaluacion_finalizada','evaluacion_id', 'evaluacion'));
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
}