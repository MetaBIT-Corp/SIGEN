<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Estudiante;
use App\DetalleInscEst;
use App\CicloMateria;
use App\CargaAcademica;
use App\Ciclo;
use App\Materia;
use DB;

class EstudianteController extends Controller
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
    //recibo materia ciclo
    public function index($id)
    {
        
        $estudiantes=DB::table('estudiante')
        ->join('detalle_insc_est','estudiante.id_est','=','detalle_insc_est.id_est')
        ->join('carga_academica','carga_academica.id_carg_aca','=','detalle_insc_est.id_carg_aca')
        ->join('materia_ciclo','materia_ciclo.id_mat_ci','=','carga_academica.id_mat_ci')
        ->where('materia_ciclo.id_mat_ci','=',$id)
        ->select('estudiante.*')->get();

        $id_mat_ci = $id;
        $materia = Materia::where('id_cat_mat',CicloMateria::where('id_mat_ci',$id)->first()->id_cat_mat)->first();
        
        return view("estudiante/listadoEstudiante",compact("estudiantes", "id_mat_ci","materia"));
    
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
    public function show($id, $id_mat_ci)
    {
        $estudiante = Estudiante::where('id_est',$id)->first();
        
        $detalles = DetalleInscEst::where('id_est',$id)->get();
        
        $mat_ci_valido = false;
        
        if(count(CicloMateria::where('id_mat_ci', $id_mat_ci)->get())){
            
            $mat_ci_valido = true; 
            
            $materia_consulta = Materia::where('id_cat_mat',CicloMateria::where('id_mat_ci', $id_mat_ci)->first()->id_cat_mat)->first();
        
            $ciclo = Ciclo::where('id_ciclo',CicloMateria::where('id_mat_ci', $id_mat_ci)->first()->id_ciclo)->first();

            $materia_consulta_codido = $materia_consulta->codigo_mat;

            $materias_cursando = array();

            $consulta_valida=false;

            foreach($detalles as $detalle){

                $materia_ciclo = CicloMateria::where('id_mat_ci',CargaAcademica::where('id_carg_aca',$detalle->id_carg_aca)->first()->id_mat_ci)->first();

                if(Ciclo::where('id_ciclo',$materia_ciclo->id_ciclo)->first()->estado){

                    $materia = Materia::where('id_cat_mat',$materia_ciclo->id_cat_mat)->first();

                    if($materia->id_cat_mat==$materia_consulta->id_cat_mat)$consulta_valida=true;

                    $materias_cursando[] = Materia::where('id_cat_mat',$materia_ciclo->id_cat_mat)->first();

                }

            }
            
            return view('estudiante.detalleEstudiante',compact('estudiante','materias_cursando','consulta_valida','materia_consulta_codido','ciclo','mat_ci_valido', 'id_mat_ci'));
            
        }
        
        
        
        return view('estudiante.detalleEstudiante',compact('mat_ci_valido'));
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
