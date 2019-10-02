<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Pregunta;
use App\Area;
use App\Grupo_Emparejamiento;
use App\Opcion;
class PreguntaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id_area, Request $request)
    {
        $area=Area::find($id_area);
        $gpos=$area->grupos_emparejamiento;
        if($request->ajax()){
            if($request->id_gpo==1){
                return dataTables()
                    ->of($gpos)
                    ->addColumn('actions','pregunta/actions')
                    ->rawColumns(['actions'])
                    ->toJson();
            }else{
                $pregunta=[];
                foreach ($gpos as $gpo) {
                    $pregunta[]=$gpo->preguntas[0];
                }
                return dataTables()
                    ->of($pregunta)
                    ->addColumn('actions','pregunta/actions')
                    ->rawColumns(['actions'])
                    ->toJson();
            }
        }

        $grupos = Grupo_Emparejamiento::where("area_id",$id_area)->get();

        return view('pregunta.index', compact('area', 'grupos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id_area)
    {
        if(!Area::where('id',$id_area)->first()){
            return redirect('/');
        }
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
        $rule_pregunta='required';
        $rule_gpo='required|exists:grupo_emparejamiento,id';
        $message_pregunta='El campo pregunta es requerido.';
        $message_gpo='Seleccione un grupo de emparejamiento valido.';

        $rules = ['pregunta' => $rule_pregunta];
        $messages = ['pregunta.required' => $message_pregunta];
        $validator = Validator::make(['pregunta'=>$request->pregunta], $rules, $messages);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $gpo=new Grupo_Emparejamiento();
        $gpo->area_id=$request->pregunta_id;//En este caso el pregunta_id trae el valor del area
        $gpo->descripcion_grupo_emp="";
        $gpo->save();

           
        $pregunta=new Pregunta();
        $pregunta->grupo_emparejamiento_id=$gpo->id;
        $pregunta->pregunta=$request->pregunta;
        $pregunta->save();

        $area=Area::find($gpo->area_id);
        if((int)$area->tipo_item->id==2){
            $opcion= new Opcion();
            $opcion->pregunta_id=$pregunta->id;
            $opcion->opcion="Verdadero";
            $opcion->correcta=true;
            $opcion->save();

            $opcion= new Opcion();
            $opcion->pregunta_id=$pregunta->id;
            $opcion->opcion="Falso";
            $opcion->correcta=false;
            $opcion->save();

        }     
        return response()->json(['success'=>'Se ha creado la pregunta exitosamente.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id_gpo,$id_preg)
    {
        $pregunta=Pregunta::find($id_preg);
        return response()->json($pregunta);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id_gpo,$id_preg)
    {
        if(!($gpo=Grupo_Emparejamiento::where('id',$id_gpo)->first())){
            return redirect('/');
        }
        $area=$gpo->area;
        $pregunta=Pregunta::where('id',$id_preg)->first();
        return view('pregunta.update',compact('area','pregunta'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id_gpo, Request $request)
    {
        $rule_pregunta='required';
        $rule_gpo='required|exists:grupo_emparejamiento,id';
        $message_pregunta='El campo pregunta es requerido.';
        $message_gpo='Seleccione un grupo de emparejamiento valido.';
        $message_mod='No modifique la pagina web, por favor.';
        
        $rules = ['pregunta' => $rule_pregunta];
        $messages = ['pregunta_id.exists'=> $message_mod, 'pregunta.required' => $message_pregunta];
        $validator = Validator::make(['pregunta'=>$request->pregunta], $rules, $messages);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $pregunta=Pregunta::where('id',(int)$request->pregunta_id)->first();
        $pregunta->pregunta=$request->pregunta;
        $pregunta->save();

        $gpo=Grupo_Emparejamiento::where('id',$id_gpo)->first();
        $area=$gpo->area;
        return response()->json(['success'=>'La pregunta se modifico exitosamente.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $pregunta=Pregunta::find((int)$request->id);
        if($pregunta->clave_area_preg_est()->count()!=0){
            $message=['error'=>'La pregunta no puede ser eliminada porque esta siendo utilizada en una evaluacion.','type'=>1];
        }else{
            $message=['success'=>'La pregunta fue eliminada exitosamente.','type'=>2];
            $gpo=$pregunta->grupo_emp;
            $gpo->delete();
        }
        return response()->json($message);
    }
}
