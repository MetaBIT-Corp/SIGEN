<?php

namespace App\Exports;

use App\Encuesta;
use App\Pregunta;
use App\Http\Controllers\EncuestaController;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ResultadosEncuestaExport implements FromView
{
    protected $encuesta_id;
    protected $excel;

    public function __construct(int $encuesta_id, bool $excel)
    {
        $this->encuesta_id = $encuesta_id;
        $this->excel = $excel;
    }

    public function view(): View
    {
    	$encuesta = Encuesta::find($this->encuesta_id);
    	$clave = ($encuesta->claves)[0];
    	$claves_areas = $clave->clave_areas;
    	$preguntas = [];
    	
    	foreach ($claves_areas as $clave_area) {
            if($clave_area->area->tipo_item->id != 4){

                $clave_area_preguntas = $clave_area->claves_areas_preguntas;
            
                foreach ($clave_area_preguntas as $clave_area_pregunta) {
                    
                    $pregunta = $clave_area_pregunta->pregunta;

                    foreach ($pregunta->opciones as $opcion) {
                        $opcion['cantidad'] = EncuestaController::obtenerCantidadRespuestas($this->encuesta_id,$opcion->id,$clave->id);
                    }
                    
                    $preguntas[] = $pregunta;

                }

            }

    	}

        return view('exports.resultados', [
        	'encuesta' => $encuesta,
            'preguntas' => $preguntas,
            'esExcel' => $this->excel
        ]);
    }
}
