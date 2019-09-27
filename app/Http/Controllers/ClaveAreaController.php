<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Response;

use App\Turno;
use App\Clave;
use App\Area;
use App\Evaluacion;
use App\CargaAcademica;
use App\CicloMateria;
use App\Clave_Area;
use App\Grupo_Emparejamiento;

class ClaveAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($turno_id)
    {

        $turno = Turno::where('id',$turno_id)->first();
        $clave = Clave::where('turno_id',$turno_id)->first();

        $evaluacion = Evaluacion::where('id',$turno->evaluacion_id)->first();
        $carga = CargaAcademica::where('id_carg_aca',$evaluacion->id_carga)->first();
        $materiac = CicloMateria::where('id_mat_ci',$carga->id_mat_ci)->first();

        $areas = Area::where("id_cat_mat",$materiac->id_mat_ci)->get();

        return view('turno.areasclave',['turno'=>$turno,'clave'=>$clave,'evaluacion'=>$evaluacion,'carga'=>$carga, 'materiac'=>$materiac, 'areas'=>$areas]);
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


        $clave_area = new Clave_Area;

        $requestData = $request->all();
        
        $rules = [
            'area_id'=> 'required',
            'clave_id' => 'required',
            'peso' => 'required|integer|lte:peso_restante|max:100|min:1',
            'cantidad' => 'required|integer|lte:cantidad_preguntas|min:0',
        ];
        
        $messages = [

            'peso.required' => 'Peso de Área no ingresado.',
            'peso.integer' => 'Peso ingresado no es un valor numérico entero.',
            'peso.max' => 'Peso ingresado es muy alto. Peso total del Área debe ser igual o menor a 100.',
            'peso.lte' => 'Peso ingresado es muy alto. Peso total del Área debe ser igual o menor a 100.',
            'peso.min' => 'Peso ingresado no es valido. Debe ser un valor mayor a 0.',

            'cantidad.required' => 'Cantidad de Preguntas Aleatorias no ingresada.',
            'cantidad.lte' => 'Cantidad de Preguntas Aleatorias ingresadas sobrepasa las preguntas disponibles del Área.',
            'cantidad.integer' => 'Cantidad de Preguntas Aleatorias ingresadas no es un valor numérico entero',
            'cantidad.min' => 'Cantidad de Preguntas Aleatorias ingresadas no es valido. Debe ser un valor numérico entero',

        ];
        
        $validator = Validator::make($requestData, $rules, $messages);

        if ($validator->fails()) {
            return response::json(array('errors'=>$validator->getMessageBag()->toarray()));
        } else{

            $clave_area->area_id = $request->area_id;
            $clave_area->clave_id = $request->clave_id;
            $clave_area->peso = (int)($request->peso);        
            $clave_area->aleatorio = (int)($request->aleatorio);

            if ($clave_area->aleatorio == 1) {
                $clave_area->numero_preguntas = $request->cantidad;
            }else{
                $clave_area->numero_preguntas = 0;
            }

            $clave_area->save();

            return response()->json($clave_area);
        }

    }

    public function storeAreaEncuesta(Request $request){

        $clave_area = new Clave_Area;

        $clave_area->area_id = $request->area_id;
        $clave_area->clave_id = $request->clave_id;        
        $clave_area->numero_preguntas = $request->numero_preguntas;
        $clave_area->aleatorio = 0;
        $clave_area->peso = 0;

        $clave_area->save();

        return response()->json($clave_area);


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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function listarPreguntas($clave_area_id){
        
        $preguntas = DB::table('clave_area as ca')
                            ->where('ca.id', $clave_area_id)
                            ->join('grupo_emparejamiento as grupo', 'grupo.area_id', '=', 'ca.area_id')
                            ->join('pregunta as p', 'grupo.id', '=', 'p.grupo_emparejamiento_id')
                            ->select('p.pregunta', 'p.created_at')
                            ->get();

        return view('claveArea.preguntasDelArea')->with(compact('preguntas'));
    }
}
