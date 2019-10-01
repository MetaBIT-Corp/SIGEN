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
use Illuminate\Support\Facades\URL;

class IntentoController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function iniciarEvaluacion($id_turno, Request $request)
    {
        //Se obtiene el estudiante logueado para recuperar sus preguntas
        $id_user = auth()->user()->id;
        $id_est=Estudiante::where('user_id',$id_user)->first()->id_est;

        //Recuperar el turno
        $turno = Turno::find($id_turno);

        //Recuperar evaluacion a la que pertenece el turno
        $evaluacion = $turno->evaluacion;

        //Recuperamos la cantidad de preguntas a mostrar en la paginacion
        $preg_per_page = $evaluacion->preguntas_a_mostrar;

        //Recuperar las claves del turno
        $claves = $turno->claves;

        //Obtener clave aleatoria segun la cantidad de claves del turno
        //$clave_de_intento=$claves[rand(0,count($claves)-1)];
        $clave_de_intento = $claves[0];

        //Verificamos si es el primer intento que realiza
        $intento=Intento::where('estudiante_id',$id_est)->first();

        //Verificamos el intento que se realizara o esta realizando
        $intento=$this->verificarIntento(0,$id_user,$clave_de_intento,$id_est);
        
        //Obtener las preguntas segun la clave asignada aleatoriamente
        //Se envia el tipo 0 para evaluaciones
        $preguntas = $this->obtenerPreguntas($clave_de_intento,0,$id_est,$intento->numero_intento);

        //Variable que contiene el array a mostrar en la paginacion
        $paginacion = $this->paginacion($request, $preg_per_page, $preguntas);
        //return dd($preguntas);
        return view('intento.intento', compact('paginacion','evaluacion','intento','clave_de_intento'));
    }

    public function iniciarEncuesta($id_clave, Request $request)
    {
        //Se obtiene el id de la persona logueada
        $id_user = auth()->user()->id;

        //Se obtiene el objeto clave para poder extraer las preguntas de la encuesta
        $clave_de_intento = Clave::find($id_clave);

        //Verificamos el intento que se realizara o esta realizando
        $intento=$this->verificarIntento(1,$id_user,$clave_de_intento);

        //Se obtienen las preguntas segun la clave
        //Se envia el tipo=1 para encuestas
        $preguntas = $this->obtenerPreguntas($clave_de_intento,1);

        //Variable que contiene el array a mostrar en la paginacion
        //Falta definir si se paginaran las encuestas OJO
        $paginacion = $this->paginacion($request, 10, $preguntas);

        return view('intento.intento', compact('paginacion'));
    }

    /**
     * Funcion privada encargada de verificar el intento, que numero de intento es y si se esta realizando actualmente.
     * @param int $tipo_intento 0 si es de evaluacion y 1 si el intento es de encuesta
     * @param int $id_user
     * @param Clave $clave_de_intento
     * @param int $id_est
     * @author Ricardo Estupinian
     */
    public static function verificarIntento($tipo_intento,$id_user,$clave_de_intento,$id_est=null){
        if($tipo_intento==0){
            //Verificamos si es el primer intento que realiza
            $intento=Intento::where('estudiante_id',$id_est)->where('clave_id',$clave_de_intento->id)->first();
        }else{
            //Se verifica si hay intento asociado con el usuario y clave especifica
            $intento=Intento::where('user_id',$id_user)->where('clave_id',$clave_de_intento->id)->first();
        }
        
        //Inicializar el intento y asignar clave a la que pertenece el turno
        $num_intento=1;
        if($intento==null){
            $intento=new Intento();
            if($tipo_intento==0){
                $intento->estudiante_id=$id_est;
            }else{
                $intento->user_id=$id_user;
            }
            $intento->clave_id=$clave_de_intento->id;
            $intento->fecha_inicio_intento=Carbon::now('America/Denver')->format('Y-m-d H:i:s');
            $intento->fecha_final_intento=null;
            $intento->numero_intento=$num_intento;
            $intento->save();
        }else{
            //Se modifica el intento existente con la nueva fecha del nuevo intento
            if($intento->fecha_final_intento!=null){
                DB::table('respuesta')->where('id_intento',$intento->id)->delete();
                $num_intento=$intento->numero_intento;
                $intento->numero_intento=$num_intento+1;
                $intento->fecha_inicio_intento=Carbon::now('America/Denver')->format('Y-m-d H:i:s');
                $intento->fecha_final_intento=null;
                $intento->save();
            }
        }
        return $intento;
    }

    /**
     * Metodo privado que devuleve las preguntas segun la clave de la evaluacion o encuesta.
     * @author Ricardo Estupinian
     * @param App\Clave $clave clave del turno o de la encuesta
     * @param int Numero que determina si se deben obtener las preguntas de una encuesta o evaluacion
     * @param int ID del estudiante logueado
     * @return Array Compuesto por el id del tipo de item,pregunta y sus opciones.
     */
    private function obtenerPreguntas($clave,$tipo, $estudiante=null,$num_intento=null)
    {
        //Recupera en un array las areas que conforman la clave (Registros de la relacion entre clave y area)
        $claves_areas = $clave->clave_areas;
    
        //Obtenemos el intento con el cual obtendremos las respuestas del Estudiante
        $intento = Intento::where('clave_id',$clave->id)->where('estudiante_id',$estudiante)->first();

        /*Recupera los objetos clave_area_pregunta de cada clave_area y lo guarda en un array
        se le pone como clave a cada posicion del array el id del tipo de item
         */
        if($tipo==0){
            foreach ($claves_areas as $clave_area) {
                $claves_areas_preguntas[$clave_area->area->tipo_item->id] = $clave_area->claves_areas_preguntas_estudiante()->where('estudiante_id',$estudiante)
                    ->where('numero_intento',$num_intento)->get();
            }
        }else{
            foreach ($claves_areas as $clave_area) {
                $claves_areas_preguntas[$clave_area->area->tipo_item->id] = $clave_area->claves_areas_preguntas;
            }
        }
        
        //dd($claves_areas_preguntas);
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
            dd($claves_areas_preguntas[$i]);
            for ($j = 0; $j < count($claves_areas_preguntas[$i]); $j++) {

                //Si no pertence a un grupo de emparejamiento crea un array con cierta estructura
                if ($i!=3) {
                    //Al estar recorriendo una pregunta, hacemos una consulta para ver si existe una respuesta para el intento que se esta realizado
                    $respuesta = Respuesta::where('id_intento',$intento->id)->where('id_pregunta',$claves_areas_preguntas[$i][$j]->pregunta->id)->get();
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
                        $respuesta = Respuesta::where('id_intento',$intento->id)->where('id_pregunta',$pregunta->id)->first();
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
                            $respuesta = Respuesta::where('id_intento',$intento->id)->where('id_pregunta',$preg->id)->first();
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
    
    public function persistence(){
        //Se obtiene el estudiante logueado para almacenar sus respuestas
        $id_user = auth()->user()->id;
        $id_est=Estudiante::where('user_id',$id_user)->first()->id_est;
        
        //Obtenemos el intento el cual se esta realizando 
        $intento = Intento::find( (int) $_GET['intento_id'] );

        //Si no hay ningún intento sin finalizar terminamos el proceso
        if(! $intento)
            return;
        
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

    //Funcion que es llamada cuando finaliza el intento en el móvil
    public function finalizarIntentoWeb($intento_id){

        //Obtener el objeto intento que se está realizando
        $intento = Intento::find($intento_id);
        
        //Lama al método calcular nota y lo guarda en la variable $nota
        $nota = $intento->calcularNota($intento_id);

        //Actualizar los datos del intento correspondiente
        $fecha_hora_actual = Carbon::now('America/Denver')->format('Y-m-d H:i:s');
        $intento->nota_intento = $nota;
        $intento->fecha_final_intento = $fecha_hora_actual;
        $intento->save();
    }
    public function revisionEvaluacion($id_intento){
        
        
        $estudiante=null;
        $intento=null;
        $respuestas = null;
        $paginacion = null;
        $evaluacion = null;
        if(auth()->check()){
            if(auth()->user()->IsStudent){
                $estudiante = Estudiante::where('user_id',auth()->user()->id)->first();
                if($id_intento==0){
                    $intento = Intento::where('estudiante_id',$estudiante->id_est)->where('fecha_final_intento', null)->first();
                }else{
                    $intento = Intento::find($id_intento)->first();
                }
                
                $respuestas = $intento->respuestas;
                $clave = $intento->clave;
                $turno = $clave->turno;
                $evaluacion = $turno->evaluacion;
                if($evaluacion->revision == 0){
                    return redirect(URL::signedRoute('listado_evaluacion', ['id' => $evaluacion->id_carga]));
                }

                //Obtener las preguntas segun la clave asignada aleatoriamente
                //Se envia el tipo 0 para evaluaciones
                $preguntas = $this->obtenerPreguntas($intento->clave,0,$estudiante->id_est,$intento->numero_intento);

                //Variable que contiene el array a mostrar en la paginacion
                $paginacion = $this->paginacionRevision( 100, $preguntas);
            }
        }
        return view('intento.revisionDeIntento')->with(compact('estudiante','intento','respuestas','paginacion','evaluacion'));
    }

    public function calificacionEvaluacion(){
        $id_intento=0;
        if(auth()->check()){
            if(auth()->user()->IsStudent){
                $estudiante = Estudiante::where('user_id',auth()->user()->id)->first();
                $intento = Intento::where('estudiante_id',$estudiante->id_est)->where('fecha_final_intento', null)->first();
                $id_intento = $intento->id;
                //metodo de calificar
                $this->finalizarIntentoWeb($id_intento);
            }
        }
        return redirect(URL::signedRoute('revision_evaluacion', ['id_intento' => $id_intento]));
    }

   


    private function paginacionRevision($preg_per_page, $array)
    {
        /*Calcular el desplazamiento segun la variable page, para determina que
        parte del array debe devolverse segun la pagina*/
        
        $pagina_actual = 0;
        
        $offset_in_array = ($pagina_actual * $preg_per_page);

        //Dividir el array segun la pagina en la que se encuentra
        $preg_pagina = array_slice($array, $offset_in_array, $preg_per_page);

        //Devolver las preguntas necesarias segun la paginacion
        $paginacion = new LengthAwarePaginator($preg_pagina, count($array), $preg_per_page);
        $paginacion->setPath('');
        return $paginacion;
    }
}
