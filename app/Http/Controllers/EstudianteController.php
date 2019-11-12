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
use Carbon\Carbon;
use DB;

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

    public function estudiantesEnEvaluacion($evaluacion_id){

        $evaluacion = Evaluacion::findOrFail($evaluacion_id);
        
        $evaluacion_finalizada = $this->evaluacionFinalizada($evaluacion_id);

        $estudiantes = DB::table('evaluacion as ev')
                            ->where('ev.id', $evaluacion_id)
                            ->join('carga_academica as ca', 'ev.id_carga', '=', 'ca.id_carg_aca')
                            ->join('detalle_insc_est as die', 'ca.id_carg_aca', '=', 'die.id_carg_aca')
                            ->join('estudiante as es', 'die.id_est', '=', 'es.id_est')
                            ->select('es.id_est', 'es.carnet', 'es.nombre')
                            ->orderBy('es.carnet', 'asc')
                            ->get();

        $estudiantes->pluck('inicio');
        $estudiantes->pluck('final');
        $estudiantes->pluck('nota');
        $estudiantes->pluck('estado'); // 0: No iniciado; 1: Iniciado; 2: Finalizado
        $estudiantes->pluck('id_intento'); 
        $estudiantes->pluck('revision_estudiante');

        foreach($estudiantes as $estudiante){
            $intento = DB::table('turno as t')
                            ->where('t.evaluacion_id', $evaluacion_id)
                            ->join('clave as c', 'c.turno_id', '=', 't.id')
                            ->join('intento as i', 'i.clave_id', '=', 'c.id')
                            ->where('i.estudiante_id', $estudiante->id_est)
                            ->select('i.fecha_inicio_intento', 'i.fecha_final_intento', 'i.nota_intento', 'i.id', 'i.revision_estudiante')
                            ->get();

            if(count($intento) > 0){
                $estudiante->inicio = $intento[0]->fecha_inicio_intento;
                $estudiante->final = $intento[0]->fecha_final_intento;
                $estudiante->nota = $intento[0]->nota_intento;
                $estudiante->id_intento = $intento[0]->id;
                $estudiante->revision_estudiante = $intento[0]->revision_estudiante;
                
                if($intento[0]->fecha_inicio_intento && $intento[0]->fecha_final_intento){
                    $estudiante->estado = 2;
                }else{
                    $estudiante->estado = 1;
                }

            }else{
                $estudiante->inicio = ' - ';
                $estudiante->final =  ' - ';
                $estudiante->nota =  ' - ';
                $estudiante->estado = 0;
                $estudiante->id_intento = 0;
                $estudiante->revision_estudiante = 0;
            }
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
}
