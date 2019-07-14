<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Estudiante;
use App\DetalleInscEst;
use App\CicloMateria;
use App\CargaAcademica;
use App\Ciclo;
use App\Materia;

class EstudianteController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $estudiante = Estudiante::where('id_est',$id)->first();
        
        $detalles = DetalleInscEst::where('id_est',$id)->get();
        
        $materias_cursando = array();
        
        foreach($detalles as $detalle){
            
            $materia_ciclo = CicloMateria::where('id_mat_ci',CargaAcademica::where('id_carg_aca',$detalle->id_carg_aca)->first()->id_mat_ci)->first();
                
            if(Ciclo::where('id_ciclo',$materia_ciclo->id_ciclo)->first()->estado){
                
                $materias_cursando[] = Materia::where('id_cat_mat',$materia_ciclo->id_cat_mat)->first();
            
            }
            
        }
        
        return view('estudiante.detalleEstudiante',compact('estudiante','materias_cursando'));
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
}
