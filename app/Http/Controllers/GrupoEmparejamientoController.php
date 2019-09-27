<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Response;

use App\Area;
use App\Grupo_Emparejamiento;
use App\Pregunta;
use App\Opcion;

class GrupoEmparejamientoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($grupo_id)
    {

        $grupo = Grupo_Emparejamiento::where("id",$grupo_id)->first();
        $preguntas = Pregunta::where("grupo_emparejamiento_id",$grupo->id)->get();

        $indice=0;

        foreach ($preguntas as $pregunta) {

            $opciones[$indice] = Opcion::where('pregunta_id',$pregunta->id)->where('correcta',1)->first();

            $opciones_incorrectas[$indice] = Opcion::where('pregunta_id',$pregunta->id)->where('correcta',0)->first();

            $indice++;

        }

        // dd($opciones_incorrectas);

        if(isset($opciones)){
            return view('grupo.index',['grupo'=>$grupo,'preguntas'=>$preguntas,'opciones'=>$opciones,'opciones_incorrectas'=>$opciones_incorrectas]);
        }else{
            return view('grupo.index',['grupo'=>$grupo]);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        $pregunta = new Pregunta;

        $request_data = $request->all();

        $rules = [
            'pregunta'=> 'required',
            'opcion' => 'required'
        ];

        $messages = [

            'pregunta.required' => 'Pregunta no ingresada.',
            'opcion.required' => 'Respuesta no ingresada.'
        ];

        $validator = Validator::make($request_data, $rules, $messages);

        if ($validator->fails()) {
            return response::json(array('errors'=>$validator->getMessageBag()->toarray()));
        }else{

            $pregunta->pregunta = $request->pregunta;
            $pregunta->grupo_emparejamiento_id = $request->idGrupo;

            $pregunta->save();

            $opcion = new Opcion;

            $opcion->pregunta_id = $pregunta->id;
            $opcion->opcion = $request->opcion;
            $opcion->correcta = 1;

            $opcion->save();

            if($request->opcionincorrecta!=""){

                $opcion_incorrecta = new Opcion;

                $opcion_incorrecta->pregunta_id = $pregunta->id;
                $opcion_incorrecta->opcion = $request->opcionincorrecta;
                $opcion_incorrecta->correcta = 0;
                $opcion_incorrecta->save();

                return response()->json(['pregunta'=>$pregunta, 'opcion'=>$opcion, 'opcion_incorrecta'=>$opcion_incorrecta]);

            }else{
                return response()->json(['pregunta'=>$pregunta, 'opcion'=>$opcion]);
            }

        }
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

        $request_data = $request->all();

        $rules = [
            'pregunta'=> 'required',
            'opcion' => 'required'
        ];

        $messages = [

            'pregunta.required' => 'Pregunta no ingresada.',
            'opcion.required' => 'Respuesta no ingresada.'
        ];

        $validator = Validator::make($request_data, $rules, $messages);

        if ($validator->fails()) {
            return response::json(array('errors'=>$validator->getMessageBag()->toarray()));
        }else{

            $pregunta = Pregunta::where('id',$request->idPregunta)->update(['pregunta'=>$request->pregunta]);
            $opcion = Opcion::where('id',$request->idOpcion)->update(['opcion'=>$request->opcion]);

            if($request->opcionincorrectaedit==null){
                Opcion::where('pregunta_id',$request->idPregunta)->where('correcta',0)->delete();
            }else{
                $count = Opcion::where('pregunta_id',$request->idPregunta)->where('correcta',0)->count();
                if($count!=0){
                    //Update
                    Opcion::where('pregunta_id',$request->idPregunta)->where('correcta',0)->update(['opcion'=>$request->opcionincorrecta]);
                }else{
                    //Create
                    $opcion_incorrecta = new Opcion;

                    $opcion_incorrecta->pregunta_id = $request->idPregunta;
                    $opcion_incorrecta->opcion = $request->opcionincorrectaedit;
                    $opcion_incorrecta->correcta = 0;

                    $opcion_incorrecta->save();

                    return response()->json(['pregunta'=>$pregunta, 'opcion'=>$opcion, 'opcion_incorrecta'=>$opcion_incorrecta]);
                }
            }

            return response()->json(['pregunta'=>$pregunta, 'opcion'=>$opcion]);
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
        Opcion::where('pregunta_id',$request->idPregunta)->delete();
        Pregunta::find($request->idPregunta)->delete();
        return back();
    }
}
