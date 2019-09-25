<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Encuesta;
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
     * Funcion para web service de materias que cursa un determinado estudiante
     * @param int $id_user ID del usuario del estudiante
     * @author Ricardo Estupinian
     */
    public function getMateriasEstudiante($id_user){
        $materias=MateriaController::materiasEstudiante($id_user);
        return response()->Json($materias);
    }

}
