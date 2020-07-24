<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use DB;
use App\Ciclo;

class CicloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ciclos = Ciclo::orderBy('anio','desc')->orderBy('num_ciclo','desc')->get();
        return view('ciclo.index',compact('ciclos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ciclo.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validando datos
        $validator = $this->validator($request);
        
        if ($validator->fails()) {
            return redirect('ciclo/create')
                        ->withErrors($validator)
                        ->withInput();
        }
        $ciclo_anterior = DB::table('ciclo')->latest()->first();
        $fecha_inicio = Carbon::parse($request->inicio_ciclo);

        // Validacion de fechas mayores al ciclo anterior
        if(isset($ciclo_anterior)){
            $fecha_fin_ciclo_anterior = Carbon::parse($ciclo_anterior->fin_ciclo);
            if(Carbon::parse($ciclo_anterior->fin_ciclo)>=$fecha_inicio){
                return redirect('ciclo/create')
                        ->withErrors(['inicio_ciclo_menor'=>'La fecha del nuevo ciclo debe de ser mayor la fecha de finalizacion del ciclo anterior.'])
                        ->withInput();
            }
        }

        // Nuevo Ciclo
        $ciclo_nuevo = new Ciclo;
        $ciclo_nuevo->inicio_ciclo = $fecha_inicio;
        $ciclo_nuevo->fin_ciclo = Carbon::parse($request->fin_ciclo);
        $ciclo_nuevo->anio = $fecha_inicio->year;
        $ciclo_nuevo->estado = 1;

        //Asignacion de numero de ciclo
        if(isset($ciclo_anterior)){
            $fecha_inicio->year == Carbon::parse($ciclo_anterior->inicio_ciclo)->year 
                ? $ciclo_nuevo->num_ciclo = $ciclo_anterior->num_ciclo+1 
                : $ciclo_nuevo->num_ciclo=1;

            // Cambiando estado de ciclo anterior
            DB::table('ciclo')
                ->where('id_ciclo', $ciclo_anterior->id_ciclo)
                ->update(['estado' => 0]);
        }else{
            $ciclo_nuevo->num_ciclo=1;
        }

        $ciclo_nuevo->save();        
        return redirect('ciclo/')->with('success','El ciclo '.$ciclo_nuevo->num_ciclo.' del '. $ciclo_nuevo->anio.' ha sido creado correctamente');
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
        $ciclo = Ciclo::where('id_ciclo',$id)->first();
        return view('ciclo.edit',compact('ciclo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validando datos
        $validator = $this->validator($request);
        
        if ($validator->fails()) {
            return redirect('ciclo/edit')
                        ->withErrors($validator)
                        ->withInput();
        }

        // Actualizando ciclo
        DB::table('ciclo')
                ->where('id_ciclo', $id)
                ->update([
                    'inicio_ciclo'=>Carbon::parse($request->inicio_ciclo),
                    'fin_ciclo'=>Carbon::parse($request->fin_ciclo),
                    'anio'=>Carbon::parse($request->inicio_ciclo)->year
                ]);
        return redirect('ciclo/')->with('success','El ciclo ha sido actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ciclo = new  Ciclo;
        if($ciclo->materias_ciclo($id)){
            return redirect('ciclo/')->with('error','El ciclo no puede ser eliminado, posee materias asociadas.');
        }else{
            DB::table('ciclo')->where('id_ciclo',$id)->delete();
            $ciclo_anterior = DB::table('ciclo')->latest()->first();
            if(isset($ciclo_anterior)){
                DB::table('ciclo')->where('id_ciclo',$ciclo_anterior->id_ciclo)
                    ->update(['estado'=>1]);
            }
            return redirect('ciclo/')->with('success','El ciclo se ha sido eliminado correctamente');
        }
    }

    // Contruccion de validator para editar y crear ciclos
    private function validator($request){
        $rules = [
            'inicio_ciclo' => 'required|date',
            'fin_ciclo' => 'required|date|after:inicio_ciclo'
        ];

        $messages = [
            'inicio_ciclo.date' => 'El campo fecha de inicio es del tipo fecha dd/mm/aaaa.',
            'fin_ciclo.date' => 'El campo fecha de finalizacion es del tipo fecha dd/mm/aaaa.',
            'inicio_ciclo.required' => 'El campo fecha de inicio del ciclo es requerido.',
            'fin_ciclo.required' => 'El campo fecha de finalizacion del ciclo es requerido.',
            'fin_ciclo.after' => 'La fecha de finalizacion del ciclo debe de ser mayor a la fecha de inicio.',
        ];
        return Validator::make($request->all(),$rules,$messages);
    }
}
