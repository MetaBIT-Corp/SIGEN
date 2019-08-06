<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Evaluacion;
use App\Pregunta;
use App\Turno;

class IntentoController extends Controller
{
    public function iniciarEvaluacion($id_turno, Request $request)
    {
        //Recuperar el turno
        $turno = Turno::find($id_turno);

        //Recuperar evaluacion a la que pertenece el turno
        $evaluacion = $turno->evaluacion;

        //Recuperamos la cantidad de preguntas a mostrar en la paginacion
        $preg_per_page = $evaluacion->preguntas_a_mostrar;
        //$preg_per_page = 7;

        //Recuperar las claves del turno
        $claves = $turno->claves;

        //Obtener clave aleatoria segun la cantidad de claves del turno
        //$clave_de_intento=$claves[rand(0,count($claves)-1)];
        $clave_de_intento = $claves[0];

        //Inicializar el intento y asignar clave aleatoriamente de las que pertenecen al turno

        //Obtener las preguntas segun la clave asignada aleatoriamente
        $preguntas = $this->obtenerPreguntas($clave_de_intento);

        //Variable que contiene el array a mostrar en la paginacion
        $paginacion=$this->paginacion($request,$preg_per_page,$preguntas);

        //return dd($preguntas);
        return view('intento', compact('paginacion'));
    }

    public function iniciarEncuesta($id_clave,Request $request)
    {
        //Se obtiene el objeto clave para poder extraer las preguntas de la encuesta
        $clave_de_intento = Clave::find($id_clave)->first();

        //Se obtienen las preguntas segun la clave
        $preguntas = $this->obtenerPreguntas($clave_de_intento);

        //Variable que contiene el array a mostrar en la paginacion
        $paginacion=$this->paginacion($request,1,$preguntas);
    }

    /**
     * Metodo privado que devuleve las preguntas segun la clave de la evaluacion o encuesta.
     * @author Ricardo Estupinian
     * @param App\Clave $clave clave del turno o de la encuesta
     * @return Array Compuesto por el id del tipo de item,pregunta y sus opciones.
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
        'tipo_item'=>id_tipo_item
        'pregunta'=> App\Pregunta -->Objeto tipo pregunta.
        'opciones'=> [App\Opcion,App\Opcion...] -->Objeto tipo opcion.
        }

        Pero si la pregunta es de emparejamiento tiene la siguiente estructura:
        {
        'tipo_item'=>3,
        'preguntas'=>[App\Pregunta,App\Preguntas ..] Preguntas que tienen el mismo gpo emparejamiento
        Por medio de cada pregunta se pueden obtener las opciones
        }
         */
        
        /*Variable que controla que no se vuelvan a recuperar las preguntas de un grupo 
        de emparejamiento*/
        $ultimo_id_gpo=0;

        for ($i = 1; $i <= count($claves_areas_preguntas); $i++) {
            for ($j = 0; $j < count($claves_areas_preguntas[$i]); $j++) {

                //Si no pertence a un grupo de emparejamiento crea un array con cierta estructura
                if ($claves_areas_preguntas[$i][$j]->pregunta->grupo_emparejamiento_id == null) {
                    $preguntas[] = ['tipo_item' => $i, 'pregunta' => $claves_areas_preguntas[$i][$j]->pregunta, 'opciones' => $claves_areas_preguntas[$i][$j]->pregunta->opciones];
                }else{
                    if($ultimo_id_gpo!=$claves_areas_preguntas[$i][$j]->pregunta->grupo_emparejamiento_id){

                        $ultimo_id_gpo=$claves_areas_preguntas[$i][$j]->pregunta->grupo_emparejamiento_id;

                        $preguntas[]=['tipo_item' => $i, 'preguntas' => Pregunta::where('grupo_emparejamiento_id',$ultimo_id_gpo)->get()];
                        
                    }
                   
                }
            }
        }
        //$preguntas=Pregunta::paginate(4);
        return $preguntas;
    }

    /**
     * Metodo privado encargado de realizar el proceso de paginacion a mostrar en la vista, por medio de este se controla cuantas preguntas se muestran y las paginas que se necesitas por la cantidad de preguntas.
     * @param Request $request 
     * @param int $preg_per_page 
     * @param Collection $array 
     * @return LengthAwarePaginator Objeto que permite la realizacion de la paginacion
     */
    private function paginacion($request,$preg_per_page,$array){
        /*Calcular el desplazamiento segun la variable page, para determina que
        parte del array debe devolverse segun la pagina*/
        if (!empty($request->input('page'))) {
            $pagina_actual = $request->input('page') - 1;
        } else {
            $pagina_actual = 0;
        }

        $offset_in_array = ($pagina_actual * $preg_per_page);

        //Dividir el array segun la pagina en la que se encuentra
        $preg_pagina = array_slice($array, $offset_in_array, $preg_per_page);

        //Devolver las preguntas necesarias segun la paginacion
        $paginacion = new LengthAwarePaginator($preg_pagina, count($array), $preg_per_page);
        $paginacion->setPath('');
        return $paginacion;
    }
}
