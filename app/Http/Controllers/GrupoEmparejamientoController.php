<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

            $opciones[$indice] = Opcion::where("pregunta_id",$pregunta->id)->first();

            $indice++;

        }

        return view('grupo.index',['grupo'=>$grupo,'preguntas'=>$preguntas,'opciones'=>$opciones]);

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
        //
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
        $pregunta = Pregunta::where('id',$request->idPregunta)->update(['pregunta'=>$request->pregunta]);
        $opcion = Opcion::where('id',$request->idOpcion)->update(['opcion'=>$request->opcion]);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
