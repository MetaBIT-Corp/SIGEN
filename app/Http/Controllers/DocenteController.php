<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Docente;
use App\CargaAcademica;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Mail;

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

		$user_id = Docente::where('id_pdg_dcn', $request['docente_id'])->first()->user_id;
		
		Docente::where('id_pdg_dcn', $request['docente_id'])->delete();

		User::destroy($user_id);

		return back()->with('notification-type','success')->with('notification-message','El Docente se ha eliminado con éxito.');
	}

	public function downloadExcel(){
		return Storage::download("plantillaExcel/ImportarDocentes.xlsx","Listado_Docentes_SIGEN.xlsx");
	}

	public function uploadExcel(Request $request){
		//Se recupera el id del user y la hora actual para guardarlo momentaneamente
        //con un nombre diferente y evitar conflictos a la hora de que hayan subidas multiples
		$id_user = auth()->user()->id;

		//Se guarda en la ruta storage/app/importExcel de manera temporal y se recupera la ruta
		$ruta=Storage::putFileAs('importExcel',$request->file('archivo'),$id_user.Carbon::now()->format('His')."Excel.xlsx");
		
		//Mensaje de error por defecto
		$message=['error'=>'Hubo un error en la importacion. Verifique que sea el formato adecuado.','type'=>1];
		
		//Se hara la importacion de las Docentes
		$spreadsheet = null;
		$data = null;

		try{
            //Se carga el archivo que subio el archivo para poder acceder a los datos
			$spreadsheet = IOFactory::load(storage_path($path = "app/".$ruta));

			//Todas las filas se convierten en un array que puede ser accedido por las letras de las columnas de archivo excel
			$data = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
			
        }catch(Exception $e){
            return response()->json($message);
		}
		
		if($spreadsheet->getActiveSheet()->getCell('G1')=="PD01"){
			
			for ($i=5; $i <= count($data) ; $i++) {
				if($data[$i]["A"]!=null&&$data[$i]["B"]!=null&&$data[$i]["C"]!=null&&$data[$i]["D"]!=null&&$data[$i]["E"]!=null&&$data[$i]["F"]!=null){
                    $pass = str_random(10);
                    $user = new User();
					$user->name = "Docente";
					$user->email = $data[$i]["C"];
					$user->role = 1;
					$user->password = bcrypt($pass);
					$user->save();

					$docente = new Docente();
					$docente->carnet_dcn = $data[$i]["A"];
					$docente->nombre_docente = $data[$i]["B"];
					$docente->descripcion_docente = $data[$i]["D"];
					$docente->anio_titulo = $data[$i]["E"];
					$docente->activo = 1;
					$docente->tipo_jornada = 1;
					$docente->id_cargo_actual = 1;
					$docente->id_segundo_cargo = 1;
					$docente->user_id = $user->id;
                    $docente->save();
                    
                    //Envio de correo
                    $data = [
                        "email" => $user->email,
                        "password" => $pass,
                        "titulo" => "Se ha registrado en SIGEN como Docente."
                    ];

                    $asunto="Creacion de usuario en SIGEN";
                    $correo = $user->email;

                    Mail::send('docente.emailNotification', $data , function($msj){
                        $msj->from("sigen.fia.eisi@gmail.com","Sigen");
                        $msj->subject("Creacion de usuario en SIGEN");
                        $msj->to("diazcolato1997@gmail.com");
                    });
				}
			}
			$message=['success'=>'La importacion de Docentes se efectuo exitosamente.','type'=>2];
			return response()->json($message);
		}else{
			$message=['error'=>'Esta plantilla no es la indicada para esta funcionalidad.','type'=>1];
			return response()->json($message);
		}
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
        //dd($request->all());
        $rules =[
            
            'nombre_docente' => ['required', 'string','min:5','max:191'],
            'descripcion_docente' => ['max:191'],
            'carnet_dcn' => ['required', 'unique:pdg_dcn_docente,carnet_dcn'],
            'anio_titulo' => ['required'],
            'email' => ['required', 'unique:users,email'],
        ];
        /* Mensaje de Reglas de Validación */
        $messages = [
            
            'nombre_docente.required' => 'Debe de ingresar el nombre del docente',
            'nombre_docente.min' => 'El nombre debe contener como mínimo 5 caracteres',
            'nombre_docente.max' => 'El nombre debe contener como máximo 191 caracteres',
            'descripcion_docente.max' => 'La descripción debe contener como máximo 191 caracteres',
            'carnet_dcn.required' => 'Debe de indicar el carnet del docente',
            'carnet_dcn.unique' => 'El carnet ya existe. Por favor ingreso uno nuevo',
            'email.required' => 'Debe de indicar el email del docente',
            'email.unique' => 'El email ya existe. Por favor ingreso uno nuevo',
            'anio_titulo.required' => 'Debe de indicar el año de titulación',
        ];

        $this->validate($request,$rules,$messages);

        $pass = str_random(10);

        //Se crea usuario del docente
        $user = new User();
        $user->name = $request->input('nombre_docente');
        $user->email = $request ->input('email');
        $user->password = bcrypt($pass);
        $user->role = 1;
        $user->save();

        //Se crea el docente
        $docente = new Docente();
        $docente->nombre_docente = $request->input('nombre_docente');
        $docente->descripcion_docente = $request->input('descripcion_docente');
        $docente->carnet_dcn = $request->input('carnet_dcn');
        $docente->anio_titulo = $request->input('anio_titulo');
        $docente->user_id = $user->id;

        if(isset($request->all()['activo']))
            $docente->activo = 1;

        $data = [
            "email" => $user->email,
            "password" => $pass,
            "titulo" => "Se ha registrado en SIGEN como Docente."
        ];

        $asunto="Creacion de usuario en SIGEN";
        $correo = $user->email;

        Mail::send('docente.emailNotification', $data , function($msj){
            $msj->from("sigen.fia.eisi@gmail.com","Sigen");
            $msj->subject("Creacion de usuario en SIGEN");
            $msj->to("dc16009@ues.edu.sv");
        });

        $docente->save();

        return redirect()->route("docentes_index")->with("notification-message", 'Docente registrado exitosamente')
                                                  ->with("notification-type", 'success');
    }

    /**
     * Función que despliega el formulario de editar docente
     * @author Edwin palacios
     */
    public function getUpdate($docente_id){
         $docente = Docente::where('id_pdg_dcn', '=', $docente_id)->first();
         $user = User::find($docente->user_id);
         $email = $user->email;
        return view('docente.updateDocente')->with(compact('docente', 'email'));
    }

    /**
     * Función que recibe el request del formulario de editar docente
     * @param Request del formulario
     * @author Edwin palacios
     */
    public function postUpdate(Request $request){
        //dd($request->all());
        $rules =[
            'nombre_docente' => ['required', 'string','min:5','max:191'],
            'descripcion_docente' => ['max:191'],
            'carnet_dcn' => ['required', 
                                Rule::unique('pdg_dcn_docente', 'carnet_dcn')
                                    ->ignore($request->input('id_pdg_dcn'), 'id_pdg_dcn')],
            'anio_titulo' => ['required'],
            'email' => ['required', 
                                Rule::unique('users', 'email')
                                    ->ignore($request->input('user_id'))],
        ];
        /* Mensaje de Reglas de Validación */
        $messages = [
            
            'nombre_docente.required' => 'Debe de ingresar el nombre del docente',
            'nombre_docente.min' => 'El nombre debe contener como mínimo 5 caracteres',
            'nombre_docente.max' => 'El nombre debe contener como máximo 191 caracteres',
            'descripcion_docente.max' => 'La descripción debe contener como máximo 191 caracteres',
            'carnet_dcn.required' => 'Debe de indicar el carnet del docente',
            'carnet_dcn.unique' => 'El carnet ya existe. Por favor ingreso uno nuevo',
            'email.required' => 'Debe de indicar el email del docente',
            'email.unique' => 'El email ya existe. Por favor ingreso uno nuevo',
            'anio_titulo.required' => 'Debe de indicar el año de titulación',
        ];

        $this->validate($request,$rules,$messages);

        
        //Se obtiene usuario del docente
        $user = User::find($request->input('user_id'));
        $user->name = $request->input('nombre_docente');
        $user->email = $request ->input('email');
        $user->save();

        //Se obtiene el docente
        $docente = Docente::where('id_pdg_dcn', '=', $request->input('id_pdg_dcn'))->first();
        $docente->nombre_docente = $request->input('nombre_docente');
        $docente->descripcion_docente = $request->input('descripcion_docente');
        $docente->carnet_dcn = $request->input('carnet_dcn');
        $docente->anio_titulo = $request->input('anio_titulo');
    
        if(isset($request->all()['activo'])){
            $docente->activo = 1;
        }else{
            $docente->activo = 0;
        }

        $docente->save();
        return redirect()->route("docentes_index")->with("notification-message", 'Datos del docente actualizados exitosamente')
                                                  ->with("notification-type", 'success');
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
