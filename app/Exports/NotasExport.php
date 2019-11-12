<?php

namespace App\Exports;

use App\Estudiante;
use App\Evaluacion;
use App\Materia;
use App\Intento;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class NotasExport implements FromView
{
    
    protected $evaluacion_id;

    public function __construct(int $evaluacion_id)
    {
        $this->evaluacion_id = $evaluacion_id;
    }

    public function view(): View
    {
    	$evaluacion = Evaluacion::find($this->evaluacion_id);
        $materia = Materia::where('id_cat_mat',$evaluacion->carga_academica->materiaCiclo->id_mat_ci)->first();
        $clave = (($evaluacion->turnos)[0]->claves)[0];
    	$estudiantes = Estudiante::all(); 

    	foreach ($estudiantes as $estudiante) {
            $intentos = Intento::where('estudiante_id',$estudiante->id_est)->where('clave_id',$clave->id)->get();
            $estudiante["nota"] = 0.00;
            
            if($intentos->count())
                $estudiante["nota"] = $intentos[0]->nota_intento;
                
    	}

        return view('exports.notas', [
            'materia' => $materia,
        	'evaluacion' => $evaluacion,
            'estudiantes' => $estudiantes
        ]);
    }
}
