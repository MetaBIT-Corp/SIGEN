<?php

namespace App\Exports;

use App\Estudiante;
use App\Evaluacion;
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
    	$estudiantes = Estudiante::all();

    	foreach ($estudiantes as $estudiante) {
    		$estudiante["nota"] = 9.65;
    	}

        return view('exports.notas', [
        	'evaluacion' => $evaluacion,
            'estudiantes' => $estudiantes
        ]);
    }
}
