<?php

namespace App\Http\Controllers;
use App\Clave;
use App\Evaluacion;
use App\Pregunta;
use App\Turno;
use App\Respuesta;
use App\Estudiante;
use App\Grupo_Emparejamiento;
use App\Intento;
use Carbon\Carbon;
use App\Clave_Area_Pregunta_Estudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class IntentoController extends Controller
{
    public function iniciarEvaluacion($id_turno, Request $request)
    {
        //Recuperar el turno
        $turno = Turno::find($id_turno);

        //Recuperar evaluacion a la que pertenece el turno
        $evaluacion = $turno->evaluacion;

        //Recuperamos la cantidad de preguntas a mostrar en la paginacion
        //$preg_per_page = $evaluacion->preguntas_a_mostrar;
        $preg_per_page = 2;

        //Recuperar las claves del turno
        $claves = $turno->claves;

        //Obtener clave aleatoria segun la cantidad de claves del turno
        //$clave_de_intento=$claves[rand(0,count($claves)-1)];
        $clave_de_intento = $claves[0];

        //Inicializar el intento y asignar clave aleatoriamente de las que pertenecen al turno


        //Se obtiene el estudiante logueado para recuperar sus preguntas
        $id_user = auth()->user()->id;
        $id_est=Estudiante::where('user_id',$id_user)->first()->id_est;

        //Obtener las preguntas segun la clave asignada aleatoriamente
        //Se envia el tipo 0 para evaluaciones
        $preguntas = $this->obtenerPreguntas($clave_de_intento,0,$id_est);

        //Variable que contiene el array a mostrar en la paginacion
        $paginacion = $this->paginacion($request, $preg_per_page, $preguntas);

        //return dd($preguntas);
        return view('intento.intento', compact('paginacion'));
    }

    public function iniciarEncuesta($id_clave, Request $request)
    {
        //Se obtiene el objeto clave para poder extraer las preguntas de la encuesta
        $clave_de_intento = Clave::find($id_clave)->first();

        //Se obtienen las preguntas segun la clave
        //Se envia el tipo=1 para encuestas
        $preguntas = $this->obtenerPreguntas($clave_de_intento,1);

        //Variable que contiene el array a mostrar en la paginacion
        //Falta definir si se paginaran las encuestas OJO
        $paginacion = $this->paginacion($request, 1, $preguntas);

        return view('intento.intento', compact('paginacion'));
    }

    /**
     * Metodo privado que devuleve las preguntas segun la clave de la evaluacion o encuesta.
     * @author Ricardo Estupinian
     * @param App\Clave $clave clave del turno o de la encuesta
     * @param int Numero que determina si se deben obtener las preguntas de una encuesta o evaluacion
     * @param int ID del estudiante logueado
     * @return Array Compuesto por el id del tipo de item,pregunta y sus opciones.
     */
    private function obtenerPreguntas($clave,$tipo, $estudiante=null)
    {
        //Recupera en un array las areas que conforman la clave (Registros de la relacion entre clave y area)
        $claves_areas = $clave->clave_areas;

        /*Recupera los objetos clave_area_pregunta de cada clave_area y lo guarda en un array
        se le pone como clave a cada posicion del array el id del tipo de item
         */
        if($tipo==0){
            foreach ($claves_areas as $clave_area) {
                $claves_areas_preguntas[$clave_area->area->tipo_item->id] = $clave_area->claves_areas_preguntas_estudiante()->where('estudiante_id',$estudiante)->get();
            }
        }else{
            foreach ($claves_areas as $clave_area) {
            $claves_areas_preguntas[$clave_area->area->tipo_item->id] = $clave_area->claves_areas_preguntas;
            }
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
        $ultimo_id_gpo = 0;

        for ($i = 1; $i <= count($claves_areas_preguntas); $i++) {
            for ($j = 0; $j < count($claves_areas_preguntas[$i]); $j++) {

                //Si no pertence a un grupo de emparejamiento crea un array con cierta estructura
                if ($i!=3) {
                    //Al estar recorriendo una pregunta, hacemos una consulta para ver si existe una respuesta para el intento que se esta realizado
                    $respuesta = Respuesta::where('id_intento',1)->where('id_pregunta',$claves_areas_preguntas[$i][$j]->pregunta->id)->get();
                    //Obtenemos todas las opciones de la pregunta
                    $opciones = $claves_areas_preguntas[$i][$j]->pregunta->opciones;
                    //Si existe respuesta procedemos a recorrer las opciones para ver cual es la seleccionada por parte del estudiante
                    if(count($respuesta)){
                    
                        foreach($opciones as $opcion){
                            //Primeramente establecemos a cada una a que no es SELECCIONADA
                            $opcion['seleccionada'] = false;
                            
                            //En caso que la opción que se esta recorriendo, sea igual a la opcion de la respuesta
                            //Establecemos a que si esta SELECCIONADA
                            if($opcion->id == $respuesta[0]->id_opcion)
                                $opcion['seleccionada'] = true;

                        }
                    }
                    $pregunta = $claves_areas_preguntas[$i][$j]->pregunta;
                    //Vamos a verificar si es texto corto, de ser asi a la pregunta le agregaremos la respuesta almacenada, en caso de existir 
                    if($i == 4){
                        $respuesta = Respuesta::where('id_intento',1)->where('id_pregunta',$pregunta->id)->first();
                        //Por default el texto esta vacio
                        $pregunta['texto'] = "";
                        //Si existe ya una respuesta, obtenemos el texto de la respuesta y lo agregamos al objeto pregunta
                        if($respuesta != null)
                            $pregunta['texto'] = $respuesta->texto_respuesta;
                    }
                    
                    $preguntas[] = ['tipo_item' => $i, 'pregunta' => $pregunta, 'opciones' => $opciones];
                } else {
                    if ($ultimo_id_gpo != $claves_areas_preguntas[$i][$j]->pregunta->grupo_emparejamiento_id) {
                        
                        $ultimo_id_gpo = $claves_areas_preguntas[$i][$j]->pregunta->grupo_emparejamiento_id;
                        //Obtenemos las preguntas para recorrerlas y sacar para cada una su opcion seleccionada como respuesta
                        $pregs = Pregunta::where('grupo_emparejamiento_id', $ultimo_id_gpo)->get();
                        
                        foreach($pregs as $preg){
                            $respuesta = Respuesta::where('id_intento',1)->where('id_pregunta',$preg->id)->first();
                            //Por defecto la seleccionada es la opcion de "Seleccione" que posee este valor
                            $preg['seleccionada'] = "opcion_0";
                            
                            //Si la respuesta existe, establecemos el valor con el id de la opción
                            if($respuesta != null)
                                $preg['seleccionada'] = "opcion_".$respuesta->id_opcion;
                        }
                        
                        $preguntas[] = ['descripcion_gpo'=>Grupo_Emparejamiento::where('id',$ultimo_id_gpo)->first()->descripcion_grupo_emp,'tipo_item' => $i, 'preguntas' => $pregs];

                    }

                }
            }
        }
        //$preguntas=Pregunta::paginate(4);
        //dd($preguntas);
        return $preguntas;
    }

    /**
     * Metodo privado encargado de realizar el proceso de paginacion a mostrar en la vista, por medio de este se controla cuantas preguntas se muestran y las paginas que se necesitas por la cantidad de preguntas.
     * @param Request $request
     * @param int $preg_per_page
     * @param Collection $array
     * @return LengthAwarePaginator Objeto que permite la realizacion de la paginacion
     */
    private function paginacion($request, $preg_per_page, $array)
    {
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

    //Funcion que es llamada cuando finaliza el intento en el móvil
    public function finalizarIntentoMovil(Request $request){
        $respuesta = new Respuesta();

        //cantidad total de preguntas que vienen desde el móvil
        $total_preguntas = $request->total_preguntas;

        //Obteniendo los valores del request y asignandolos a la tabla respuesta
        $respuesta->id_pregunta = $request->pregunta_id;        //pregunts
        $respuesta->id_opcion = $request->opcion_id;            //opcion
        $respuesta->id_intento = $request->intento_id;          //intento 
        $respuesta->texto_respuesta = $request->texto_respuesta;//texto escrito en caso sea respues corta

        //Guardar el objeto respuesta
        $respuesta->save();

        //Consulta la cantidad de respuestas que ha sido guardadas del intento correspondiente
        $num_actual = Respuesta::where('id_intento', $request->intento_id)->get();

        //Verifica si todas las respuestas que venian del movil ya se guardaron en la base de datos mysql
        if($total_preguntas == count($num_actual)){
            //Lama al método calcular nota
            $nota = $this->calcularNota($request->intento_id);

            //Actualizar los datos del intento correspondiente
            $intento = Intento::find($request->intento_id);
            $fecha_hora_actual = Carbon::now('America/Denver')->format('Y-m-d H:i:s');
            $intento->nota_intento = $nota;
            $intento->fecha_final_intento = $fecha_hora_actual;
            $intento->save();
        }
    }

    //Función para calcular la nota del intento
    public function calcularNota($intento_id){
        $intento = Intento::find($intento_id); //Obtener el intento
        $estudiante_id = $intento->estudiante->id_est; //Obtener al estudiante que realizó el intento
        $nota = 0.0;

        foreach($intento->respuestas as $respuesta){

            //Si la respuesta que seleccionó en la pregunta es correcta
            if($respuesta->opcion->correcta==1){

                //Obtener la pregunta a la que pertenece la respuesta
                $pregunta_id = $respuesta->pregunta->id;

                //Consulta para obtener el objeto clave_area_pregunta_estudiante al que pertenece la pregunta
                $cape = Clave_Area_Pregunta_Estudiante::where('estudiante_id', $estudiante_id)
                                                        ->where('pregunta_id', $pregunta_id)
                                                        ->first();
        
                //Obtener la clave_aera a la que pertenece la pregunta
                $clave_area = $cape->clave_area;

                //Obtener la modalidad a la que pertecene la pregunta
                $modalidad = $clave_area->area->tipo_item_id;

                //Obtener el peso de la pregunta
                $peso = $clave_area->peso;

                //Cuenta la cantidad de preguntas que tiene el objeto clave_are
                $cantidad_preguntas = count($clave_area->claves_areas_preguntas_estudiante);

                //Verifica si la pregunta pertenece a modalidad de respuesta corta
                if($modalidad==4){
                    $txt_respuesta = strtolower($respuesta->texto_respuesta);
                    $txt_opcion = strtolower($respuesta->opcion->opcion);
                    if(strcmp($txt_respuesta, $txt_opcion) == 0){
                        
                        //Calcula la ponderación de la pregunta
                        $nota += ($peso/$cantidad_preguntas)/10;
                    }
                }else{
                    //Calcula la ponderación de la pregunta
                    $nota += ($peso/$cantidad_preguntas)/10;
                }
            }
        }

        return $nota;
        
    }
    
    public function persistence(){
        //Se obtiene el estudiante logueado para almacenar sus respuestas
        $id_user = auth()->user()->id;
        $id_est=Estudiante::where('user_id',$id_user)->first()->id_est;
        //Obtenemos el intento el cual se esta realizando
        $intento = Intento::find(1);
        
        //Obtenemos la variable respuestas pasada como param en metodo get, esta cadena posee este formato:
        //pregunta_##=opcion_##/pregunta_##=opcion_##, en donde ## denota el identificador de cada objeto
        //y la / separa cada par de pregunta-opcion
        $respuestas = preg_split('[-]', $_GET['respuestas']);
        
        foreach($respuestas as $respuesta){
            //Apartir del mismo formato en el que vienen las respuesta, procedemos a recorrer cada respuesta
            //obteniendo la pregunta_id y la opcion_id
            $pregunta_id = (int) preg_split('[_]', preg_split('[=]', $respuesta)[0])[1];
            $opcion_arr = preg_split('[_]', preg_split('[=]', $respuesta)[1]);
            //Si el split anterior es igual a dos significa que no es pregunta de respuesta corta
            if(count($opcion_arr)==2){
                $opcion_id = (int) $opcion_arr[1];
                //Si opcion_id es distinto del valor por defecto, significa que hay una respuesta valida, por lo que procedemos a crear la respuesta
                if($opcion_id != 0){
                
                    //Hacemos una busqueda de una respuesta que sea del mismo intento y para la pregunta que estamos recorriendo
                    $respuesta = Respuesta::where('id_intento',$intento->id)->where('id_pregunta',$pregunta_id)->first();
                    //Verificamos si existe, si no existe creamos nueva respuesta para la pregunta
                    //si existe solo cambiamos la opcion_id asociada
                    if($respuesta != null){
                        //En caso que ya exista la respuesta a la pregunta especifica, solo cambiamos el id de la opcion seleccionada
                        $respuesta->id_opcion = $opcion_id;
                        $respuesta->save();
                    }
                    else{
                        //En caso que sea la primera vez que se responda la pregunta
                        //procedemos a crear la Respuesta
                        $respuesta = new Respuesta();
                        $respuesta->id_pregunta = $pregunta_id;
                        $respuesta->id_opcion = $opcion_id;
                        $respuesta->id_intento = $intento->id;
                        $respuesta->save();
                    }
                    
                }
            }
            else{
                //Este caso indica que es respuesta corta, lo que significa que el proceso requiere un algunas tareas diferentes
                $texto_respuesta = $opcion_arr[0];
                //Hacemos una busqueda de una respuesta que sea del mismo intento y para la pregunta que estamos recorriendo
                $respuesta = Respuesta::where('id_intento',$intento->id)->where('id_pregunta',$pregunta_id)->first();
                
                //Verificamos si existe, si no existe creamos nueva respuesta para la pregunta
                //si existe solo cambiamos el texto_respuesta asociada
                if($respuesta != null){
                    //En caso que ya exista la respuesta a la pregunta especifica, solo cambiamos el id de la opcion seleccionada
                    $respuesta->texto_respuesta = $texto_respuesta;
                    $respuesta->save();
                }
                else{
                    //En caso que sea la primera vez que se responda la pregunta
                    //procedemos a crear la Respuesta
                    
                    if($texto_respuesta != ""){
                        //Si el texto es diferente de vacio, entonces procedemos a crear la respuesta
                        $respuesta = new Respuesta();
                        $respuesta->id_pregunta = $pregunta_id;
                        $respuesta->texto_respuesta = $texto_respuesta;
                        $respuesta->id_intento = $intento->id;
                        $respuesta->save();    
                    }
                }
            }
        }
    }
}
