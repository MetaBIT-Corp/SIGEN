<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Encuesta;
use App\CargaAcademica;
use App\Evaluacion;
use App\User;
use Illuminate\Support\Facades\Hash;

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
     * @return Json que contiene el registro del user.
     */ 
    public function accesoUserMovil($email, $password){
        $user_no_autenticado = User::where('email',$email)->first();
        $user_autenticado = null;

        if($user_no_autenticado->IsStudent){
        	if(Hash::check($password, $user_no_autenticado->password)){
            	$user_autenticado = $user_no_autenticado;
        	}
        }
        
        $data = ['user'=>$user_autenticado,'pass'=>$password];
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

}
