<?php

namespace App\Http\Controllers;

use App\Turno;
use App\Evaluacion;
use Illuminate\Http\Request;
use DateTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
    
class TurnoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('turno.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        
        if(!Evaluacion::find($id))
            return redirect('/home');
        
        return view('turno.create', compact('id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($id, Request $request)
    {
        $requestData = $request->all();
        $requestData['id_evaluacion'] = $id;
        
        $rules = [
            'fecha_inicio_turno' => 'required',
            'fecha_final_turno' => 'required',
            'contraseña' => 'required|min:8'
        ];
        
        $messages = [
            'contraseña.required' => 'La contraseña es requerida.',
            'contraseña.min' => 'La contraseña debe presentar como mínimo 8 caracteres.',
            'fecha_inicio_turno.required' => 'La fecha/hora de inicio es requerida.',
            'fecha_final_turno.required' => 'La fecha/hora de fin es requerida.'
        ];
        
        $validator = Validator::make($requestData, $rules, $messages);
        
        

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $requestData['fecha_inicio_turno'] = DateTime::createFromFormat('m/d/Y H:i A', $request->input('fecha_inicio_turno'))->format('Y-m-d H:i:s');
        $requestData['fecha_final_turno'] = DateTime::createFromFormat('m/d/Y H:i A', $request->input('fecha_final_turno'))->format('Y-m-d H:i:s');
        
        if(!Carbon::parse($requestData['fecha_final_turno'])->gt(Carbon::parse($requestData['fecha_inicio_turno'])))
            return back()->with('notification-type','danger')->with('notification-message','La fecha/hora de fin debe ser mayor que la fecha/hora de inicio!')->withInput();
        
        $fecha_hora_actual = Carbon::now('America/Denver')->format('Y-m-d H:i:s');
        $fecha_hora_actual_alert = DateTime::createFromFormat('Y-m-d H:i:s', $fecha_hora_actual)->format('m/d/Y h:i A');
            
        if(!Carbon::parse($requestData['fecha_inicio_turno'])->gt(Carbon::parse($fecha_hora_actual)))
            return back()->with('notification-type','danger')->with('notification-message','La fecha/hora de inicio debe ser mayor que la fecha/hora actual ('.$fecha_hora_actual_alert.')!')->withInput();

        $turno = new Turno();
        $turno->fecha_inicio_turno = $requestData['fecha_inicio_turno'];
        $turno->fecha_final_turno = $requestData['fecha_final_turno'];
        $turno->contraseña = bcrypt($requestData['contraseña']);
        $turno->visibilidad = 0;

        if(isset($requestData['visibilidad']))
            $turno->visibilidad = 1;
        
        $turno->id_evaluacion = $requestData['id_evaluacion'];
        $turno->save();
        
        return back()->with('notification-type','success')->with('notification-message','El turno se ha registrado con éxito!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Turno  $turno
     * @return \Illuminate\Http\Response
     */
    public function show(Turno $turno)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Turno  $turno
     * @return \Illuminate\Http\Response
     */
    public function edit(Turno $turno)
    {
        return view('turno.edit', compact('turno'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Turno  $turno
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Turno $turno)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Turno  $turno
     * @return \Illuminate\Http\Response
     */
    public function destroy(Turno $turno)
    {
        //
    }
    
    public function getDuracionEvaluacion($id_evaluacion)
    {
        return Evaluacion::find($id_evaluacion)->duracion;
    }
}
