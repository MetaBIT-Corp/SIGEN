<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pregunta;
use App\Area;
use App\Grupo_Emparejamiento;
class PreguntaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id_area)
    {
        $area=Area::find($id_area);
        return view('pregunta.create',compact('area'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Falta Validacion de Datos
        if(empty($request->gpo_emp)){
            $gpo=new Grupo_Emparejamiento();
            $gpo->area_id=$request->area_id;
            $gpo->descripcion_grupo_emp="";
            $gpo->save();
        }else{
            $gpo=Grupo_Emparejamiento::find((int)$request->gpo_emp);
        }
        $pregunta=new Pregunta();
        $pregunta->grupo_emparejamiento_id=$gpo->id;
        $pregunta->pregunta=$request->pregunta;
        $pregunta->save();
        return redirect()->action('PreguntaController@create',[$gpo->area->id])->with('success','Se agrego correctamente');
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
    public function edit($id_area)
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //Falta 
        $pregunta=Pregunta::find((int)$request->pregunta_id);
        $pregunta->delete();
        $gpo=$pregunta->grupo_emp;
    }
}
