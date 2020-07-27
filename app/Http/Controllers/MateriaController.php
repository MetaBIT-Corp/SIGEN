<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Materia;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

use Illuminate\Http\Request;

use Response;

class MateriaController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $materias = Materia::all();
        $last_update = Materia::max('updated_at');
        return view('materia.index',['materias'=>$materias,'last_update'=>$last_update]);
    }

    public function update(Request $request)
    {
        $request_data = $request->all();

        $rules = [
            'materia'=> 'required',
            'materia_codigo' => 'required',
            'materia_preguntas' => 'required|integer|min:1'
        ];

        $messages = [

            'materia.required' => 'Nombre de Materia no ingresado.',
            'materia_codigo.required' => 'Código de Materia no ingresado',
            'materia_preguntas.required' => 'Cantidad de Preguntas de Materia no ingresada',

            'materia_preguntas.integer' => 'Cantidad de Preguntas de Materia ingresada no es un valor numérico entero',
            'materia_preguntas.min' => 'Cantidad de Preguntas de Materia ingresada no es valido. Debe ser un valor numérico entero, mayor a cero'

        ];

        $validator = Validator::make($request_data, $rules, $messages);

        if ($validator->fails()) {
            return response::json(array('errors'=>$validator->getMessageBag()->toarray()));
        }else{
            if($request->materia_id!=null){
                
                $materia = Materia::where('id_cat_mat',$request->materia_id)->update(
                [
                    'codigo_mat'=>$request->materia_codigo,
                    'nombre_mar'=>$request->materia,
                    'es_electiva'=>$request->materia_tipo,
                    'maximo_cant_preguntas'=>$request->materia_preguntas
                ]);

            }else{

                $materia = new Materia;
                $materia->codigo_mat =$request->materia_codigo;
                $materia->nombre_mar =$request->materia;
                $materia->es_electiva =$request->materia_tipo;
                $materia->maximo_cant_preguntas =$request->materia_preguntas;
                $materia->save();

            }

            return response()->json(['materia'=>$materia]);
            
        }

    }

    public function downloadExcel(){
        return Storage::download("plantillaExcel/ImportarMaterias.xlsx","Listado_Materias_SIGEN.xlsx");
    }

    //PMS01 I1 (A5:Codigo / B5:Materia / C5:Es_Electiva / D5:MaxPreguntas)

    public function uploadExcel(Request $request){
        //Se recupera el id del user y la hora actual para guardarlo momentaneamente
        //con un nombre diferente y evitar conflictos a la hora de que hayan subidas multiples
        $id_user = auth()->user()->id;

        //Se guarda en la ruta storage/app/importExcel de manera temporal y se recupera la ruta
        $ruta=Storage::putFileAs('importExcel',$request->file('archivo'),$id_user.Carbon::now()->format('His')."Excel.xlsx");
        
        //Mensaje de error por defecto
        $message=['error'=>'Hubo un error en la importación. Verifique que sea el formato adecuado.','type'=>1];
        
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
        
        if($spreadsheet->getActiveSheet()->getCell('G1')=='PMS01'){
            $inserted = 0;
            $total = 0;

            for ($i=8; $i <= count($data) ; $i++) {

                if($data[$i]["A"]!=null)
                    $total++;

                if($data[$i]["A"]!=null && $data[$i]["B"]!=null && $data[$i]["C"]!=null && $data[$i]["D"]!=null){

                    //Validaciones

                    if(Materia::where('codigo_mat', $data[$i]["A"])->count() > 0)
                        continue;
                    if(Materia::where('nombre_mar', $data[$i]["B"])->count() > 0)
                        continue;

                    if($data[$i]["C"]!='Si' && $data[$i]["C"]!='No')
                        continue;

                    $materia = new Materia();
                    $materia->codigo_mat = $data[$i]["A"];
                    $materia->nombre_mar = $data[$i]["B"];

                    if($data[$i]["C"]=='Si'){
                        $materia->es_electiva = 1;
                    }
                    else{
                        $materia->es_electiva = 0;
                    }

                    $materia->maximo_cant_preguntas = (int)$data[$i]["D"];                    

                    $materia->save();

                    $inserted++;

                }
            }
            //Eliminar el archivo subido, solo se utiliza para la importacion y luego de desecha
            Storage::delete($ruta);

            $message=['success'=>'La importación de Materias se efectuo éxitosamente; se insertarón ' . $inserted . '/' . $total . ' registros.' ,'type'=>2];
            return response()->json($message);
        }else{
            //Eliminar el archivo subido, solo se utiliza para la importacion y luego de desecha
            Storage::delete($ruta);
            
            $message=['error'=>'Esta plantilla no es la indicada para esta funcionalidad.','type'=>1];
            return response()->json($message);
        }
    }

    public function listar(Request $request)
    {
        $id = auth()->user()->id;
        $mat_con_eva = new Materia();

        $recuperar_ciclos=0;
        if($request->ciclos){
            switch ($request->ciclos) {
            case 1:
                //Recuperamos 5 ciclos
                $recuperar_ciclos=5;
                break;
            case 2:
                //Recuperamos 10 ciclos
                $recuperar_ciclos=10;
                break;
            case 3:
                //Recuperamos 20 ciclos
                $recuperar_ciclos=20;
                break;
            }
        }
        
        switch (auth()->user()->role) {
            case 0:
                $materias = array();

                /*Se recupera los ciclos ordenados de mayor a menor con respecto al id, asumiendo que el ultimo registro en la tabla ciclo es el ciclo que se encuentra activo*/

                if($recuperar_ciclos!=0){
                    $ciclos = DB::table('ciclo')->orderBy('id_ciclo', 'desc')->take($recuperar_ciclos)->get();
                }else{
                     $ciclos = DB::table('ciclo')->orderBy('id_ciclo', 'desc')->get();
                }
                
                //dd($ciclos);
                foreach ($ciclos as $ciclo) {
                    /*Se crea un array asociativo donde se guardaran las materias por ciclo Ejemplo materias[1][] esto significa que del ciclo con id 1 obtienen todas las materias*/
                    $materias[$ciclo->id_ciclo] = DB::table('cat_mat_materia')
                        ->join('materia_ciclo', 'cat_mat_materia.id_cat_mat', '=', 'materia_ciclo.id_cat_mat')
                        ->join('ciclo', 'ciclo.id_ciclo', '=', 'materia_ciclo.id_ciclo')
                        ->where('ciclo.id_ciclo', '=', $ciclo->id_ciclo)
                        ->select('cat_mat_materia.*', 'materia_ciclo.*', 'ciclo.*')
                        ->get();
                }
                /*Se ordena inversamente el arreglo bidimensional tomando como parametro la llave, que son los id de los ciclos*/

                krsort($materias);

                return view("materia.listadoMateria", compact("materias", "ciclos", "mat_con_eva"));
                break;

            case 1:
                $materias = array();
                /*Se recupera los ciclos ordenados de mayor a menor con respecto al id, asumiendo que el ultimo registro en la tabla ciclo es el ciclo que se encuentra activo*/

                if($recuperar_ciclos!=0){
                    $ciclos = DB::table('ciclo')->orderBy('id_ciclo', 'desc')->take($recuperar_ciclos)->get();
                }else{
                     $ciclos = DB::table('ciclo')->orderBy('id_ciclo', 'desc')->get();
                }
                foreach ($ciclos as $ciclo) {
                    $materias[$ciclo->id_ciclo] = DB::table('cat_mat_materia')
                        ->join('materia_ciclo', 'cat_mat_materia.id_cat_mat', '=', 'materia_ciclo.id_cat_mat')
                        ->join('carga_academica', 'carga_academica.id_mat_ci', '=', 'materia_ciclo.id_mat_ci')

                        ->join('pdg_dcn_docente', function ($join) {
                            //Consulta Avanzada donde se determina de que docente se trata
                            $idUser = auth()->user()->id;
                            $join->on('pdg_dcn_docente.id_pdg_dcn', '=', 'carga_academica.id_pdg_dcn')
                                ->where('pdg_dcn_docente.user_id', '=', $idUser);
                        })
                        ->join('ciclo', 'ciclo.id_ciclo', '=', 'materia_ciclo.id_ciclo')
                        ->where('ciclo.id_ciclo', '=', $ciclo->id_ciclo)
                        ->select('cat_mat_materia.*', 'materia_ciclo.*','carga_academica.id_carg_aca')
                        ->get();                        
                }
                return view("materia.listadoMateria", compact("materias", "ciclos", "mat_con_eva"));
                break;
            case 2:
                $ciclo    = DB::table("ciclo")->where("estado", "=", 1)->get();
                $materias = MateriaController::materiasEstudiante($id);
                return view("materia.listadoMateria", compact("materias", "ciclo", "mat_con_eva"));
                break;
        }
    }



    /**
     * Funcion para obtener las materias segun estudiante y el ciclo activo
     * @param int $id_user ID del usuario del estudiante
     * @author Ricardo Estupinian
     */
    public static function materiasEstudiante($id_user){
        $materias = DB::table('cat_mat_materia')
            ->join('materia_ciclo', 'cat_mat_materia.id_cat_mat', '=', 'materia_ciclo.id_cat_mat')
            ->join('carga_academica', 'carga_academica.id_mat_ci', '=', 'materia_ciclo.id_mat_ci')
            ->join('detalle_insc_est', 'detalle_insc_est.id_carg_aca', '=', 'carga_academica.id_carg_aca')
            ->join('estudiante', 'estudiante.id_est', '=', 'detalle_insc_est.id_est')
            ->where('estudiante.user_id', '=',$id_user)
            ->join('ciclo', 'ciclo.id_ciclo', '=', 'materia_ciclo.id_ciclo')
            ->where('ciclo.estado', '=', 1)
            ->select('cat_mat_materia.*', 'materia_ciclo.*','carga_academica.*','ciclo.*','detalle_insc_est.*')->get();
        return $materias;
    }
}
