<?php

namespace App\Http\Controllers;

use App\Turno;
use App\Evaluacion;
use App\Clave;
use App\CargaAcademica;
use App\CicloMateria;
use App\Estudiante;
use App\Clave_Area;
use App\Intento;
use App\Area;
use App\Encuesta;
use App\Encuestado;
use App\Pregunta;
use App\Clave_Area_Pregunta;
use App\Clave_Area_Pregunta_Estudiante;
use App\Opcion;
use App\Grupo_Emparejamiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
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

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index($id)
    {
        $evaluacion = Evaluacion::find($id);
        $evaluacion_id = $evaluacion->id;
        $turnos = $evaluacion->turnos;
        $nombre_evaluacion = $evaluacion->nombre_evaluacion;

        //Obtenemos fecha:hora actual
        $fecha_hora_actual = Carbon::now('America/Denver')->format('Y-m-d H:i:s');

        //A continuación procedemos a verificar si los turnos podran ser editados y/o eliminados
        //Si el turno no ha empezado, se podrá editar y eliminar
        //Si el turno ya empezo, solo podra editar, todo, menos la fecha de inicio
        //Si el turno ya termino, no se podra editar ni elimianr
        foreach($turnos as $turno){
            //Si la fecha final no es mayor que la actual, que significa que ya termino, no tendra acciones disponibles
             if(!Carbon::parse($turno->fecha_final_turno)->gt(Carbon::parse($fecha_hora_actual)))
                 $turno['acciones'] = false;
             else
                //Caso contrario, si tendrá acciones disponibles
                 $turno['acciones'] = true;
            
            //Procedemos a verificar si tendrá la opción de eliminar, si ya empezo, no podrá
            if(!Carbon::parse($turno->fecha_inicio_turno)->gt(Carbon::parse($fecha_hora_actual)))
                 $turno['accion_delete'] = false;
             else
                //Caso contrario, si podrá
                 $turno['accion_delete'] = true;
                 
            //Procedemos a cambiar el formato de las fechas de "Y-m-d H:i:s" a "d/m/Y h:i A"     
            $turno->fecha_inicio_turno = DateTime::createFromFormat('Y-m-d H:i:s', $turno->fecha_inicio_turno)->format('d/m/Y h:i A');
            $turno->fecha_final_turno = DateTime::createFromFormat('Y-m-d H:i:s', $turno->fecha_final_turno)->format('d/m/Y h:i A');
            
        }
        
        return view('turno.index', compact('turnos','nombre_evaluacion','evaluacion_id','evaluacion'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        //Si no existe la Evaluación lo redireccionamos a /home
        $evaluacion = Evaluacion::find($id);
        $id = $evaluacion->id;
        if(!$evaluacion)
            return redirect('/home');
        
        return view('turno.create', compact('id','evaluacion'));
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
        
        //Cambiamos el formato de las fechas de "d/m/Y H:i A" a "Y-m-d H:i:s", que es el que se maneja en la base de datos
        
        $requestData['fecha_inicio_turno'] = DateTime::createFromFormat('d/m/Y H:i A', $request->input('fecha_inicio_turno'))->format('Y-m-d H:i:s');
        $requestData['fecha_final_turno'] = DateTime::createFromFormat('d/m/Y H:i A', $request->input('fecha_final_turno'))->format('Y-m-d H:i:s');
        
        //Validamos que la fecha final sea mayor que la fecha de inicio

        if(!Carbon::parse($requestData['fecha_final_turno'])->gt(Carbon::parse($requestData['fecha_inicio_turno'])))
            return back()->with('notification-type','danger')->with('notification-message','La fecha/hora de fin debe ser mayor que la fecha/hora de inicio.')->withInput();
        
        //Obtenemos la fecha actual, para luego hacer una validación que la fecha de inicio sea mayor que la actual

        $fecha_hora_actual = Carbon::now('America/Denver')->format('Y-m-d H:i:s');
        $fecha_hora_actual_alert = DateTime::createFromFormat('Y-m-d H:i:s', $fecha_hora_actual)->format('d/m/Y h:i A');
        
        //Validamos que la fecha de inicio sea mayor que la fecha actual    
        if(!Carbon::parse($requestData['fecha_inicio_turno'])->gt(Carbon::parse($fecha_hora_actual)))
            return back()->with('notification-type','danger')->with('notification-message','La fecha/hora de inicio debe ser mayor que la fecha/hora actual ('.$fecha_hora_actual_alert.').')->withInput();
        
        //Calculamos la diferencia entre la fecha final e inicial del turno

        $diff_fin_inicio = Carbon::parse($requestData['fecha_final_turno'])->diffInMinutes(Carbon::parse($requestData['fecha_inicio_turno']));

        //Obtenemos la duración de la Evaluación
        $duracion_evaluacion = Evaluacion::find($requestData['evaluacion_id'])->duracion;
        
        //Validamos que la duración del turno no sea menor que la duración de la Evaluación        
        if(! (($diff_fin_inicio - $duracion_evaluacion) >= 0) )
            return back()->with('notification-type','danger')->with('notification-message','La diferencia en horas entre la fecha/hora de fin y la fecha/hora de inicio debe ser mayor que la duración de la evaluación ('. $duracion_evaluacion.' horas).')->withInput();

        //Procedemos a crear el nuevo Turno, luego que paso todas las validaciones
        $turno = new Turno();
        $turno->fecha_inicio_turno = $requestData['fecha_inicio_turno'];
        $turno->fecha_final_turno = $requestData['fecha_final_turno'];

        $turno->contraseña = $requestData['contraseña'];
        //bcrypt($requestData['contraseña']);


        $turno->evaluacion_id = $requestData['evaluacion_id'];
        $turno->visibilidad = 0;

        $turno->save();


        $clave = new Clave();

        $clave->turno_id = $turno->id;
        $clave->numero_clave = 1;

        $clave->save();
        return redirect(URL::signedRoute('listado_turnos', ['id' => $id]));
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
        //Obteniendo la clave del turno
        $claves = Clave::where('turno_id', $turno_id)->get();   /*Consulta ahora se hace por turno_id*/

        //dd(count($claves[0]->clave_areas[0]->claves_areas_preguntas));

        $fecha_hora_actual = Carbon::now('America/Denver')->format('Y-m-d H:i:s');
        
        $turno = Turno::find($turno_id);
        $turno['iniciado'] = false;
        $visibilidad = $turno->visibilidad;
        
        if(!Carbon::parse($turno->fecha_inicio_turno)->gt(Carbon::parse($fecha_hora_actual)))
            $turno['iniciado'] = true;
            
        
        $turno->fecha_inicio_turno = DateTime::createFromFormat('Y-m-d H:i:s', $turno->fecha_inicio_turno)->format('d/m/Y h:i A');
        $turno->fecha_final_turno = DateTime::createFromFormat('Y-m-d H:i:s', $turno->fecha_final_turno)->format('d/m/Y h:i A');

        // Parte de René

        $clave = Clave::where('turno_id',$turno_id)->first();
        $evaluacion = Evaluacion::where('id',$turno->evaluacion_id)->first();
        $carga = CargaAcademica::where('id_carg_aca',$evaluacion->id_carga)->first();
        $materiac = CicloMateria::where('id_mat_ci',$carga->id_mat_ci)->first();
        $areas = Area::where("id_cat_mat",$materiac->id_mat_ci)->get();
        $id_areas = Clave_Area::where('clave_id',$clave->id)->pluck('area_id')->toArray();
        $peso_turno = (int)(Clave_Area::where('clave_id',$clave->id)->sum('peso'));

        $encuesta = false;

        return view('turno.edit', compact('turno', 'id', 'claves', 'clave','evaluacion','carga','materiac','areas','id_areas','peso_turno','encuesta', 'visibilidad'));
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
        
        //Cambiamos el formato de las fechas de "d/m/Y H:i A" a "Y-m-d H:i:s", que es el que se maneja en la base de datos
        $requestData['fecha_inicio_turno'] = DateTime::createFromFormat('d/m/Y H:i A', $request->input('fecha_inicio_turno'))->format('Y-m-d H:i:s');
        $requestData['fecha_final_turno'] = DateTime::createFromFormat('d/m/Y H:i A', $request->input('fecha_final_turno'))->format('Y-m-d H:i:s');
        
        //Validamos que la fecha final sea mayor que la fecha de inicio
        if(!Carbon::parse($requestData['fecha_final_turno'])->gt(Carbon::parse($requestData['fecha_inicio_turno'])))
            return back()->with('notification-type','danger')->with('notification-message','La fecha/hora de fin debe ser mayor que la fecha/hora de inicio.')->withInput();
        
        //Obtenemos la fecha actual, para luego hacer una validación que la fecha de inicio sea mayor que la actual
        
        $fecha_hora_actual = Carbon::now('America/Denver')->format('Y-m-d H:i:s');
        $fecha_hora_actual_alert = DateTime::createFromFormat('Y-m-d H:i:s', $fecha_hora_actual)->format('d/m/Y h:i A');
        
        //Si no ha iniciado, significa que se puede editar fecha de inicio, por lo que ese necesario validar que la fecha de inicio sea mayor que la fecha actual
        if(!$requestData["iniciado"]){
            
            if(!Carbon::parse($requestData['fecha_inicio_turno'])->gt(Carbon::parse($fecha_hora_actual)))
                return back()->with('notification-type','danger')->with('notification-message','La fecha/hora de inicio debe ser mayor que la fecha/hora actual ('.$fecha_hora_actual_alert.').')->withInput();
            
        }

        //Calculamos la diferencia entre la fecha final e inicial del turno
        
        $diff_fin_inicio = Carbon::parse($requestData['fecha_final_turno'])->diffInMinutes(Carbon::parse($requestData['fecha_inicio_turno']));

        //Obtenemos la duración de la Evaluación
        $duracion_evaluacion = Evaluacion::find($evaluacion_id)->duracion;
        
        //Validamos que la duración del turno no sea menor que la duración de la Evaluación        

        if(! (($diff_fin_inicio - $duracion_evaluacion) >= 0) )
            return back()->with('notification-type','danger')->with('notification-message','La diferencia en horas entre la fecha/hora de fin y la fecha/hora de inicio debe ser mayor que la duración de la evaluación ('. $duracion_evaluacion.' horas).')->withInput();
        
        //Finalmente, luego de pasar todas las validaciones, procedemos a actualizar el turno
        $turno = Turno::find($id);
        $turno->fecha_inicio_turno = $requestData['fecha_inicio_turno'];
        $turno->fecha_final_turno = $requestData['fecha_final_turno'];
        
        //Si ha ingresado nueva contraseña, la cambiamos
        if(isset($requestData['contraseña']) and $requestData['contraseña'] != null)
           $turno->contraseña = $requestData['contraseña']; 
            //bcrypt($requestData['contraseña']);
        
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

    /**
     * Funcion para duplicar turno y su configuracion.
     * @param int $id_eva
     * @param int $id_turno
     * @author Ricardo Estupinian
     */
    public function duplicarTurno($id_eva,$id_turno,Request $request){
        //Recuperando turno a duplicar
        $turno=Turno::find($id_turno);

        //Duplicando turno
        $turno_duplicado=new Turno();
        $turno_duplicado->fecha_inicio_turno = $turno->fecha_inicio_turno;
        $turno_duplicado->fecha_final_turno = $turno->fecha_final_turno;
        $turno_duplicado->contraseña = $turno->contraseña;
        $turno_duplicado->evaluacion_id = $turno->evaluacion_id;
        $turno_duplicado->visibilidad = $turno->visibilidad;
        $turno_duplicado->save();

        //Obteniendo clave del turno
        $clave=$turno->claves[0];

        //Duplicando clave
        $clave_duplicada=new Clave();
        $clave_duplicada->turno_id=$turno_duplicado->id;
        $clave_duplicada->numero_clave=$clave->numero_clave;
        $clave_duplicada->save();

        //Recuperando los claves_areas de la clave
        $claves_areas=$clave->clave_areas;

        //Duplicando los clavea areas
        foreach ($claves_areas as $clave_area) {
            $clave_area_duplicada=new Clave_Area();
            $clave_area_duplicada->area_id= $clave_area->area_id;
            $clave_area_duplicada->clave_id=$clave_duplicada->id;
            $clave_area_duplicada->numero_preguntas=$clave_area->numero_preguntas;
            $clave_area_duplicada->aleatorio=$clave_area->aleatorio;
            $clave_area_duplicada->peso= $clave_area->peso;
            $clave_area_duplicada->save();
        }
        return back()->with('notification-type','success')->with('notification-message','El turno se duplico correctamente!');
    }

}
