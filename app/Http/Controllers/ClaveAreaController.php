<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Turno;
use App\Clave;
use App\Area;
use App\Evaluacion;
use App\CargaAcademica;
use App\CicloMateria;
use App\Clave_Area;
use App\Grupo_Emparejamiento;
use Illuminate\Support\Facades\DB;

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

        $clave_area->area_id = $request->area_id;
        $clave_area->clave_id = $request->clave_id;
        $clave_area->numero_preguntas = $request->cantidad;
        $clave_area->aleatorio = (int)($request->aleatorio);
        $clave_area->peso = (int)($request->peso);

        $clave_area->save();

        return back();

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
