<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Docente;
use App\CargaAcademica;
use App\User;

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


	 /**
     * Función que despliega el formulario de crear docente
     * @author Edwin palacios
     */
    public function getCreate(){
        return view('docente.createDocente');

    }

    /**
     * Función que recibe el request del formulario de crear docente
     * @param Request del formulario
     * @author Edwin palacios
     */
    public function postCreate(Request $request){
        dd($request->all());
        return redirect('docentes_index');


    }

    /**
     * Función que despliega el formulario de editar docente
     * @author Edwin palacios
     */
    public function getUpdate(){
        return view('docente.updateDocente');

    }

    /**
     * Función que recibe el request del formulario de editar docente
     * @param Request del formulario
     * @author Edwin palacios
     */
    public function postUpdate(Request $request){
        dd($request->all());
        return redirect('docentes_index');
    }

	/**
      * Cambia el estado del usuario del docente, si está bloqueado lo habilita y viceversa
      * @author Enrique Menjívar <mt16007@ues.edu.sv>
      * @param  Request $request Datos enviados desde el frontend
      */
     public function changeStateDocente(Request $request){
        $id_docente = $request->input('docente_id');
        $docente = Docente::where('id_pdg_dcn', $id_docente)->first();

        $user = User::findOrFail($docente->user_id);

        if($user->enabled == 1){
            $user->enabled = 0;
            $message = 'El Docente con carnet <em><b>' . $docente->carnet_dcn  . '</b></em> fue bloqueado con éxito';
        }else{
            $user->enabled = 1;
            $message = 'El Docente con carnet <em><b>' . $docente->carnet_dcn  . '</b></em> fue desbloqueado con éxito';
        }

        $user->save();

        return back()->with('message', $message);
     }
}
