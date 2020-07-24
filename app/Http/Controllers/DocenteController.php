<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Docente;
use App\CargaAcademica;

class DocenteController extends Controller
{
    
    public function docentes_materia_ciclo($id_mat_ci){
    	$docentes = DB::table('carga_academica')
    					->where('carga_academica.id_mat_ci', $id_mat_ci)
    					->join('pdg_dcn_docente', 'pdg_dcn_docente.id_pdg_dcn', '=', 'carga_academica.id_pdg_dcn')->get();


    	return view('docente.docentesMateriaCiclo')->with(compact('docentes'));
	}
	
	public function index(){
		$docentes = Docente::all();
		$last_update = Docente::max('updated_at');

		return view('docente.index', compact('docentes','last_update'));
	}

	public function destroy(Request $request){

		$cant_ca = CargaAcademica::where('id_pdg_dcn', $request['docente_id'])->count(); 
		
		if($cant_ca > 0)
			return back()->with('notification-type','warning')->with('notification-message','El Docente no puede ser eliminado, debido a que posee carga académica.');

		Docente::where('id_pdg_dcn', $request['docente_id'])->delete();

		return back()->with('notification-type','success')->with('notification-message','El Docente se ha eliminado con éxito.');
	}

	public function downloadExcel(){
		return Storage::download("plantillaExcel/ImportarDocentes.xlsx","Listado_Docentes_SIGEN.xlsx");
	}

}
