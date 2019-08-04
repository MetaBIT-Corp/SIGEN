<?php

namespace App\Http\Controllers;

use App\Evaluacion;
use App\Turno;

class IntentoController extends Controller
{
    public function iniciarEvaluacion($id_turno)
    {
        //Recuperar el turno
        $turno = Turno::find($id_turno);

        //Recuperar evaluacion a la que pertenece el turno
        $evaluacion = $turno->evaluacion;

        //Recuperamos la cantidad de preguntas a mostrar en la paginacion
        $cant_preg_paginacion = $evaluacion->preguntas_a_mostrar;

        //Recuperar las claves del turno
        $claves = $turno->claves;

        //Obtener clave aleatoria segun la cantidad de claves del turno
        //$clave_de_intento=$claves[rand(0,count($claves)-1)];
        $clave_de_intento = $claves[0];

        //Inicializar el intento y asignar clave aleatoriamente de las que pertenecen al turno

        //Obtener las preguntas
        $preguntas=$this->obtenerPreguntas($clave_de_intento);

        return dd($preguntas);
    }

    public function iniciarEncuesta($id_clave)
    {
        //Se obtiene el objeto clave para poder extraer las preguntas de la encuesta
        $clave_de_intento=Clave::find($id_clave)->first();

        //Se obtienen las preguntas segun la clave
        $preguntas=$this->obtenerPreguntas($clave_de_intento);
    }

    /**
     * Metodo que devuleve las preguntas segun la clave de la evaluacion o encuesta.
     * @author Ricardo Estupinian
     * @param App\Clave $clave clave del turno o de la encuesta
     * @return Array compuesto por el id del tipo de item,pregunta y sus opciones.
     */
    private function obtenerPreguntas($clave)
    {
        //Recupera en un array las areas que conforman la clave (Registros de la relacion entre clave y area)
        $claves_areas = $clave->clave_areas;

        /*Recupera los objetos clave_area_pregunta de cada clave_area y lo guarda en un array
          se le pone como clave a cada posicion del array el id del tipo de item
        */
        foreach ($claves_areas as $clave_area) {
            $claves_areas_preguntas[$clave_area->area->tipo_item->id] = $clave_area->claves_areas_preguntas;
        }

        /*Se recorre el array de claves_areas_preguntas, el primer bucle recorre los clave_area
        basandose siempre en el id del tipo de item, luego el segundo bucle se utiliza para recorrer
        cada clave_area_pregunta y obtener la pregunta en si.
        Se crea la estructura siguiente para devolver las preguntas:
        {
            'tipo_item':id_tipo_item
            'pregunta': App\Pregunta -->Objeto tipo pregunta.
            'opciones': [App\Opcion,App\Opcion...] -->Objeto tipo opcion.
        }
        */
        for ($i = 1; $i <= count($claves_areas_preguntas); $i++) {
            for ($j = 0; $j < count($claves_areas_preguntas[$i]); $j++) {
                $preguntas[] = ['tipo_item' => $i, 'pregunta' => $claves_areas_preguntas[$i][$j]->pregunta, 'opciones' => $claves_areas_preguntas[$i][$j]->pregunta->opciones];
            }
        }
        return $preguntas;
    }
}
