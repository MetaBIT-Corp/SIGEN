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
    public function index($id)
    {
        $evaluacion = Evaluacion::find($id);
        $turnos = $evaluacion->turnos;
        $nombre_evaluacion = $evaluacion->nombre_evaluacion;
        $evaluacion_id = $evaluacion->id;
        
        foreach($turnos as $turno){
            
            $fecha_hora_actual = Carbon::now('America/Denver')->format('Y-m-d H:i:s');
            
             if(!Carbon::parse($turno->fecha_final_turno)->gt(Carbon::parse($fecha_hora_actual)))
                 $turno['acciones'] = false;
             else
                 $turno['acciones'] = true;
            
            if(!Carbon::parse($turno->fecha_inicio_turno)->gt(Carbon::parse($fecha_hora_actual)))
                 $turno['accion_delete'] = false;
             else
                 $turno['accion_delete'] = true;
                 
                 
            $turno->fecha_inicio_turno = DateTime::createFromFormat('Y-m-d H:i:s', $turno->fecha_inicio_turno)->format('d/m/Y h:i A');
            $turno->fecha_final_turno = DateTime::createFromFormat('Y-m-d H:i:s', $turno->fecha_final_turno)->format('d/m/Y h:i A');
            
        }
        
        //dd($turnos);
        
        return view('turno.index', compact('turnos','nombre_evaluacion','evaluacion_id'));
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
        $requestData['evaluacion_id'] = $id;
        
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
        
        $requestData['fecha_inicio_turno'] = DateTime::createFromFormat('d/m/Y H:i A', $request->input('fecha_inicio_turno'))->format('Y-m-d H:i:s');
        $requestData['fecha_final_turno'] = DateTime::createFromFormat('d/m/Y H:i A', $request->input('fecha_final_turno'))->format('Y-m-d H:i:s');
        
        if(!Carbon::parse($requestData['fecha_final_turno'])->gt(Carbon::parse($requestData['fecha_inicio_turno'])))
            return back()->with('notification-type','danger')->with('notification-message','La fecha/hora de fin debe ser mayor que la fecha/hora de inicio.')->withInput();
        
        $fecha_hora_actual = Carbon::now('America/Denver')->format('Y-m-d H:i:s');
        $fecha_hora_actual_alert = DateTime::createFromFormat('Y-m-d H:i:s', $fecha_hora_actual)->format('d/m/Y h:i A');
            
        if(!Carbon::parse($requestData['fecha_inicio_turno'])->gt(Carbon::parse($fecha_hora_actual)))
            return back()->with('notification-type','danger')->with('notification-message','La fecha/hora de inicio debe ser mayor que la fecha/hora actual ('.$fecha_hora_actual_alert.').')->withInput();
        
        $diff_fin_inicio = Carbon::parse($requestData['fecha_final_turno'])->diffInHours(Carbon::parse($requestData['fecha_inicio_turno']));
        $duracion_evaluacion = Evaluacion::find($requestData['evaluacion_id'])->duracion;
                
        if(! (($diff_fin_inicio - $duracion_evaluacion) >= 0) )
            return back()->with('notification-type','danger')->with('notification-message','La diferencia en horas entre la fecha/hora de fin y la fecha/hora de inicio debe ser mayor que la duración de la evaluación ('. $duracion_evaluacion.' horas).')->withInput();

        $turno = new Turno();
        $turno->fecha_inicio_turno = $requestData['fecha_inicio_turno'];
        $turno->fecha_final_turno = $requestData['fecha_final_turno'];
        $turno->contraseña = bcrypt($requestData['contraseña']);
        $turno->evaluacion_id = $requestData['evaluacion_id'];
        $turno->visibilidad = 0;

        if(isset($requestData['visibilidad']))
            $turno->visibilidad = 1;
        
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
    public function edit( $id, $turno_id )
    {
        $fecha_hora_actual = Carbon::now('America/Denver')->format('Y-m-d H:i:s');
        
        $turno = Turno::find($turno_id);
        $turno['iniciado'] = false;
        
        if(!Carbon::parse($turno->fecha_inicio_turno)->gt(Carbon::parse($fecha_hora_actual)))
            $turno['iniciado'] = true;
            
        
        $turno->fecha_inicio_turno = DateTime::createFromFormat('Y-m-d H:i:s', $turno->fecha_inicio_turno)->format('d/m/Y h:i A');
        $turno->fecha_final_turno = DateTime::createFromFormat('Y-m-d H:i:s', $turno->fecha_final_turno)->format('d/m/Y h:i A');
        
        return view('turno.edit', compact('turno', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Turno  $turno
     * @return \Illuminate\Http\Response
     */
    public function update($evaluacion_id, Request $request, $id)
    {
        $requestData = $request->all();
        
        $rules = [
            'fecha_inicio_turno' => 'required',
            'fecha_final_turno' => 'required',
            'contraseña' => 'nullable|min:8'
        ];
        
        $messages = [
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
        
        $requestData['fecha_inicio_turno'] = DateTime::createFromFormat('d/m/Y H:i A', $request->input('fecha_inicio_turno'))->format('Y-m-d H:i:s');
        $requestData['fecha_final_turno'] = DateTime::createFromFormat('d/m/Y H:i A', $request->input('fecha_final_turno'))->format('Y-m-d H:i:s');
        
        if(!Carbon::parse($requestData['fecha_final_turno'])->gt(Carbon::parse($requestData['fecha_inicio_turno'])))
            return back()->with('notification-type','danger')->with('notification-message','La fecha/hora de fin debe ser mayor que la fecha/hora de inicio.')->withInput();
        
        $fecha_hora_actual = Carbon::now('America/Denver')->format('Y-m-d H:i:s');
        $fecha_hora_actual_alert = DateTime::createFromFormat('Y-m-d H:i:s', $fecha_hora_actual)->format('d/m/Y h:i A');
        
        if(!$requestData["iniciado"]){
            
            if(!Carbon::parse($requestData['fecha_inicio_turno'])->gt(Carbon::parse($fecha_hora_actual)))
                return back()->with('notification-type','danger')->with('notification-message','La fecha/hora de inicio debe ser mayor que la fecha/hora actual ('.$fecha_hora_actual_alert.').')->withInput();
            
        }
        
        $diff_fin_inicio = Carbon::parse($requestData['fecha_final_turno'])->diffInHours(Carbon::parse($requestData['fecha_inicio_turno']));
        $duracion_evaluacion = Evaluacion::find($evaluacion_id)->duracion;
                
        if(! (($diff_fin_inicio - $duracion_evaluacion) >= 0) )
            return back()->with('notification-type','danger')->with('notification-message','La diferencia en horas entre la fecha/hora de fin y la fecha/hora de inicio debe ser mayor que la duración de la evaluación ('. $duracion_evaluacion.' horas).')->withInput();
        
        $turno = Turno::find($id);
        $turno->fecha_inicio_turno = $requestData['fecha_inicio_turno'];
        $turno->fecha_final_turno = $requestData['fecha_final_turno'];
        
        if(isset($requestData['contraseña']) and $requestData['contraseña'] != null)
           $turno->contraseña = bcrypt($requestData['contraseña']);
        
        $turno->visibilidad = 0;

        if(isset($requestData['visibilidad']))
            $turno->visibilidad = 1;
        
        $turno->save();
        
        return back()->with('notification-type','success')->with('notification-message','El turno se ha actualizado con éxito!');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Turno  $turno
     * @return \Illuminate\Http\Response
     */
    public function destroy($evaluacion_id, $id)
    {
        Turno::find($id)->delete();
        return back();
    }
    
    public function getDuracionEvaluacion($evaluacion_id)
    {
        return Evaluacion::find($evaluacion_id)->duracion;
    }
}
