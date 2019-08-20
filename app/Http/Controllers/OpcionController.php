<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Area;
use App\Grupo_Emparejamiento;
use App\Pregunta;
use App\Opcion;

class OpcionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($pregunta_id)
    {

        /*
        *La función index devuelve un listado de las opciones de la pregunta especificada
        *Tomando en cuenta el tipo de item que almacena el área al que pertenece la pregunta.
        *Por lo que se hace una recopilación de diversos parámetros para prepara la vista adecuada.
        */

        /*Obteniendo pregunta desde Ruta.*/
        $pregunta = Pregunta::where("id",$pregunta_id)->first();
        /*Obteniendo Opciones a partir de Pregunta.*/
        $opciones = Opcion::where("pregunta_id",$pregunta->id)->get();
        /*Obteniendo Grupo_Emparejamiento a partir de Pregunta.*/
        $grupo = Grupo_Emparejamiento::where("id",$pregunta->grupo_emparejamiento_id)->first();
        /*Obteniendo Área a partir de Grupo_Emparejamiento.*/
        $area = Area::where("id",$grupo->area_id)->first();
        /*Obteniendo ID de tipo_item a partir del Área.*/
        $tipo_opcion = $area->tipo_item_id;

        /*
        *Switch para redireccionar a la Vista adecuada para cada tipo de Opción
        *Cada vista necesitará recibir como parametros:
        * - El ID de la pregunta.
        * - El texto de las opciones de la pregunta.
        * - El ID del tipo de item del Área a la que pertenece la pregunta.
        */
        switch ($tipo_opcion) {

            /*Tipo Item: Opción Múltiple*/
            case 1:
                return view('opcion.index_om',['opciones'=>$opciones,'pregunta'=>$pregunta,'tipo_opcion'=>$tipo_opcion]);
            break;

            /*Tipo Item: Verdadero/Falso*/
            case 2:
                return view('opcion.index_vf',['opciones'=>$opciones,'pregunta'=>$pregunta,'tipo_opcion'=>$tipo_opcion]);
            break;

            /*Tipo Item: Grupo Emparejamiento*/
            case 3:
                return view('opcion.index_ge',['opciones'=>$opciones,'pregunta'=>$pregunta,'tipo_opcion'=>$tipo_opcion]);
            break;

            /*Tipo Item: Respuesta Corta*/
            case 4:            
                return view('opcion.index_rc',['opciones'=>$opciones,'pregunta'=>$pregunta,'tipo_opcion'=>$tipo_opcion]);            
            break;

        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($pregunta_id)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        /*
        *La función store es la encargada de almacenar nuevas opciones para las
        *preguntas que lo permitan. Los parametros y valores de las nuevas opciones
        *son enviados desde un formulario en la vista designada al tipo de la nueva
        *opcion que se quiera crear.
        */

        /*Obteniendo la pregunta a la que se le agregará la opción (A partir del Request).*/
        $pregunta = Pregunta::where("id",$request->pregunta_id)->first();
        /*Obteniendo el Grupo Emparejamiento al que pertenece la pregunta (A partir de la pregunta).*/
        $grupo = Grupo_Emparejamiento::where("id",$pregunta->grupo_emparejamiento_id)->first();
        /*Obteniendo el Área al que pertenece el Grupo Emparejamiento, para así obtener su Tipo de Item.*/
        $area = Area::where("id",$grupo->area_id)->first();
        /*Obteniendo el Tipo Item del Área.*/
        $tipo_opcion = $area->tipo_item_id;

        /*
        *Switch para seleccionara el método adecuada para almacenar la opción.
        *Cada Opción, dependiendo el Área, requiere un tratamiento especial.
        *Dependiento del Tipo de Opción que almacena el Área de la pregunta, así
        *se almacenarán los valores de los atributos de la opción.
        */

        switch ($tipo_opcion) {

            /*Tipo Item: Opción Múltiple*/
            case 1:

                /*Creación de nueva instancia de Opcion*/
                $opcion = new Opcion;

                /*
                *Asignación de parámetros a los atributos de nueva instancia.
                *Valores obtenidos desde la Request enviada por formulario POST.
                */
                $opcion->pregunta_id=$request->pregunta_id;
                $opcion->opcion=$request->opcion;
                $opcion->correcta=(int)($request->correcta);

                /*Almacenando la nueva instancia como registro en Base de Datos*/
                $opcion->save();

            break;

            /*Tipo Item: Verdadero/Falso*/
            case 2:
                # code...
            break;
            /*Tipo Item: Grupo de Emparejamiento*/
            case 3:
                # code...
            break;

            /*Tipo Item: Respuesta Corta*/
            case 4:

                /*Creación de nueva instancia de Opcion*/
                $opcion = new Opcion;

                /*
                *Asignación de parámetros a los atributos de nueva instancia.
                *Valores obtenidos desde la Request enviada por formulario POST.
                */
                $opcion->pregunta_id=$request->pregunta_id;
                $opcion->opcion=$request->opcion;
                $opcion->correcta=1;

                /*Almacenando la nueva instancia como registro en Base de Datos*/
                $opcion->save();

            break;
        }

        return back();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        /*
        *La función update es la que se encarga de actualizar los
        *registros en la DB, dependiendo de los datos enviados a 
        *través de la Request enviada desde las diferentes vistas.
        *Para actualizar los registros se han creada diversos métodos,
        *ya que cada tipo de Opción requiere de un tratamiento especial
        *en los valores de sus parámetros.
        */

        /*
        *El Tipo de la Opción a modificar es enviada desde un form 
        *a través de la Request.
        */

        switch ($request->tipo_opcion) {

            /*Tipo Item: Opción Múltiple*/
            case 1:

                Opcion::where("id",$request->id)->update(["opcion"=>$request->opcion,"correcta"=>(int)($request->correctaEdit)]);

                return back();

            break;

            /*Tipo Item: Verdadero/Falso*/
            case 2:

                $opciones = Opcion::where('pregunta_id',$request->pregunta_id)->get();

                if ($request->correcta==0) {

                    $opciones[0]->correcta=1;
                    $opciones[1]->correcta=0;

                }else{

                    $opciones[0]->correcta=0;
                    $opciones[1]->correcta=1;

                }

                $opciones[0]->save();
                $opciones[1]->save();

                return back();

            break;
            
            /*Tipo Item: Grupo de Emparejamiento*/
            case 3:

                Opcion::where('id',$request->id)->update(["opcion"=>$request->opcion]);

                return back();
                
            break;
            
            /*Tipo Item: Respuesta Corta*/
            case 4:
            
                Opcion::where('id',$request->id)->update(["opcion"=>$request->opcion]);

                return back();

            break;

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        /*
        *La función destroy es la encargada de eliminar
        *la opción seleccionada por el usuario.
        */

        $opcion=Opcion::find($request->id);

        $opcion->delete();

        return back();

    }

}