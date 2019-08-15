<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Materia;

use App\Tipo_Item;
use App\Area;
use App\Docente;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

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
        $success=false;
        $areas=$materia->areas;

        return view('area.index', compact('areas','materia','success'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id_materia)
    {
        $tipos_item = Tipo_Item::all();
        return view('area.create', compact("id_materia","tipos_item"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($id_materia, Request $request)
    {
        if(!Materia::where('id_cat_mat',$id_materia)->first())
            return redirect('/');
        
        $rules = [
            'tipo_item' => 'required|exists:tipo_item,id',
            'titulo' => 'required|min:15|max:191'
        ];
        
        $messages = [
            'tipo_item.required' => 'El tipo de item es requerido.',
            'tipo_item.exists' => 'El tipo de item seleccionado no es válido.',
            'titulo.required' => 'El título es requerido.',
            'titulo.min' => 'El título debe presentar como mínimo 15 caracteres.',
            'titulo.max' => 'El título debe presentar como máximo 191 caracteres.'
        ];
        
        $validator = Validator::make($request->all(), $rules, $messages);
        
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $area = new Area();
        $area->id_cat_mat = $id_materia;
        $area->id_pdg_dcn = Docente::where('user_id', Auth::user()->id)->first()->id_pdg_dcn;
        $area->tipo_item_id = $request->input('tipo_item');
        $area->titulo = $request->input('titulo');
        $area->save();
        
        return back()->with('notification-type','success')->with('notification-message','El área se ha registrado con éxito!');
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

    public function respuesta($id_materia=null, Request $request){
        $success=false;
        if($request->isMethod("POST")&&!empty($request->find)&&!empty($request->id_mat)){
            $materia=Materia::where('id_cat_mat',$request->id_mat)->first();
            $areas=$materia->areas()->where('titulo','LIKE',$request->find.'%')->get();
            return view('area.response', compact('areas','success'));
        }
        if($id_materia==0){
            $success=false;
            $id_materia=$request->id_mat;
        }
        $materia=Materia::where('id_cat_mat',$id_materia)->first();
        $areas=$materia->areas;
        return view('area.response', compact('areas','success'));
    }
}
