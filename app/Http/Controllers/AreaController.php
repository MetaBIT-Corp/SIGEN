<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Materia;
use App\Area;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id_materia, Request $request)
    {
        if(!Materia::where('id_cat_mat',$id_materia)->first()){
            return redirect('/');
        }

        $materia=Materia::where('id_cat_mat',$id_materia)->first();
        $areas=$materia->areas;
        if($request->isMethod("POST")){
            return view('area.response', compact('areas'));
        }

        return view('area.index', compact('areas'));
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
        return redirect()->action('AreaController@areas',[$id]);
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
        $data=$request->all();
        $area=Area::where('id',(int)$data["id_area"])->first();
        $area->titulo=$data["titulo"];
        $area->save();

        $id_mat=$area->materia->id_cat_mat;  
        return redirect()->action('AreaController@respuesta',[$id_mat]); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $data=$request->all();
        $area=Area::where('id',(int)$data["id_area"])->first();
        $id_mat=$area->materia->id_cat_mat;  
        $area->delete();

        return redirect()->action('AreaController@respuesta',[$id_mat]); 
    }

    public function respuesta($id_materia){
        $materia=Materia::where('id_cat_mat',$id_materia)->first();
        $areas=$materia->areas;
        return view('area.response', compact('areas'));
    }
}
