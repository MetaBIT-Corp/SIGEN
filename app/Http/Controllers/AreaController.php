<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Materia;

use App\Tipo_Item;
use App\Area;
use App\Docente;
use App\Clave_Area;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     * @author Ricardo Estupinian
     * @return \Illuminate\Http\Response
     */
    public function index($id_materia, Request $request)
    {   
        if(!Materia::where('id_cat_mat',$id_materia)->first()){
            return redirect('/');
        }
        $materia=Materia::where('id_cat_mat',$id_materia)->first();

        if($request->ajax()){
            $areas=$materia->areas;
            $a=[];
            $a=$this->areasArray($areas);
            return dataTables()
                    ->of($a)
                    ->addColumn('actions','area/actions')
                    ->rawColumns(['actions'])
                    ->toJson();
        }
        $encuesta=false;
        return view('area.index', compact('materia','encuesta'));
    }

    /**
     * Mostrar el listado de areas de las areas segun docente.
     * @author Ricardo Estupinian
     */
    public function indexEncuesta(Request $request){
        $id_user = auth()->user()->id;
        $id_docente=Docente::where('user_id',$id_user)->first()->id_pdg_dcn;
        $areas=Area::where('id_pdg_dcn',$id_docente)->get();
        $materia=Materia::where('id_cat_mat',1)->first();
        $encuesta=true;
        if($request->ajax()){
            $a=[];
            $a=$this->areasArray($areas);
            return dataTables()
                    ->of($a)
                    ->addColumn('actions','area/actions')
                    ->rawColumns(['actions'])
                    ->toJson();
        }

        return view('area.index', compact('materia','encuesta'));

    }
    /**
     * Funcion que retorna un array perzonalizado para devolverlo en el dataTable
     * @param Eloquent Areas
     * @return Array
     */
    private function areasArray($areas){
        $a=[];
        if(count($areas)>0){
                //Construccion de array perzonalizado para mostrar en Data Table
                for($i=0;$i<count($areas);$i++){
                    $a[]=[
                        "id_area"=>$areas[$i]->id,
                        "id"=>$i+1,
                        "titulo"=>$areas[$i]->titulo,
                        "tipo_item"=>$areas[$i]->tipo_item->nombre_tipo_item,
                    ];
                }
        }
        return $a;
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id_mat,$id)
    {
        $area=Area::find($id);
        return response()->json($area);
    }
    
    /**
     * Update the specified resource in storage.
     * 
     * @author Ricardo Estupinian
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
        return response()->json(['success'=>'El area fue modificada exitosamente']);
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
        if($area->claves_areas()->count()!=0){
            $message=['error'=>'El area no puede ser eliminada porque esta siendo utilizada en una evaluacion.','type'=>1];
        }else{
            $message=$message=['success'=>'El area fue eliminada exitosamente.','type'=>2];
            $area->delete();
        }
        return response()->json($message);
    }
}