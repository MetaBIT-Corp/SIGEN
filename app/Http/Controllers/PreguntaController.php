<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
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

        if(!$request->has('gpo_emp')){
            $rules = ['pregunta' => $rule_pregunta];
            $messages = ['pregunta.required' => $message_pregunta];
            $validator = Validator::make(['preguta'=>$request->pregunta], $rules, $messages)->validate();

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $gpo=new Grupo_Emparejamiento();
            $gpo->area_id=$request->area_id;
            $gpo->descripcion_grupo_emp="";
            $gpo->save();

        }else{
            $rules = [
                'pregunta' => $rule_pregunta,
                'gpo_emp' => $rule_gpo
            ];
            $messages=[
                'pregunta.required'=>$message_pregunta,
                'gpo_emp.exists'=>$message_gpo,
                'gpo_emp.required'=>'No modifique la pagina web, por favor.'
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

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
    public function edit($id_area,$id_preg)
    {
        if(!Area::where('id',$id_area)->first()){
            return redirect('/');
        }
        $area=Area::find($id_area);
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
    public function update($id_area,$id_preg, Request $request)
    {
        $rule_preg_id='required|exists:pregunta,id';
        $rule_pregunta='required';
        $rule_gpo='required|exists:grupo_emparejamiento,id';
        $message_pregunta='El campo pregunta es requerido.';
        $message_gpo='Seleccione un grupo de emparejamiento valido.';
        $message_mod='No modifique la pagina web, por favor.';

        if(!$request->has('gpo_emp')){
            $rules = ['pregunta_id'=>$rule_preg_id,'pregunta' => $rule_pregunta];
            $messages = ['pregunta_id.exists'=> $message_mod, 'pregunta.required' => $message_pregunta];
            $validator = Validator::make(['preguta'=>$request->pregunta], $rules, $messages)->validate();

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $pregunta=Pregunta::where('id',$request->pregunta_id);
            $pregunta->pregunta=$request->pregunta;
            $pregunta->save();


        }else{
            $rules = [
                'pregunta' => $rule_pregunta,
                'gpo_emp' => $rule_gpo
            ];
            $messages=[
                'pregunta.required'=>$message_pregunta,
                'gpo_emp.exists'=>$message_gpo,
                'gpo_emp.required'=>$message_mod
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $pregunta=Pregunta::where('id',(int)$request->pregunta_id)->first();
            $pregunta->pregunta=$request->pregunta;
            $pregunta->grupo_emparejamiento_id=(int)$request->gpo_emp;
            $pregunta->save();
        }
        
        return redirect()->action('PreguntaController@edit',[$id_area,$pregunta->id])->with('success','Se agrego correctamente');
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
