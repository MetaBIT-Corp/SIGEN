<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;

use App\CicloMateria;
use App\CargaAcademica;
use App\Docente;
use App\Materia;
use App\Ciclo;
use DB;

class MateriaCicloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $materias = DB::table('cat_mat_materia')
            ->join('materia_ciclo', 'cat_mat_materia.id_cat_mat', '=', 'materia_ciclo.id_cat_mat')
            ->join('ciclo', 'ciclo.id_ciclo', '=', 'materia_ciclo.id_ciclo')
            ->where('ciclo.id_ciclo', '=', $id)
            ->select('cat_mat_materia.*','materia_ciclo.id_mat_ci')
            ->get();
        $ciclo = Ciclo::where('id_ciclo',$id)->first();
        return view('materia_ciclo.index',compact('materias','ciclo'));
    }

    /**
     * Funcion encargada de retornar la plantilla Excel para posteiormente importar materias al ciclo.
     * @return Excel.xlsx
     * @author Ricardo Estupinian
     */
    public function downloadExcel($id){
        $ciclo = Ciclo::where('id_ciclo',$id)->first();
        $nombre_descarga="Materias_Ciclo_".$ciclo->num_ciclo.'_Año_'.$ciclo->anio.'.xlsx';
        $ruta='plantillaExcel/ImportarMateriaCiclo.xlsx';
        return Storage::download($ruta,$nombre_descarga);
    }

    /**
     * Funcion que permite que el docente suba el archivo excel con el formato requerido lo valide e importe las materias ciclo
     * @var int
     * @author Ricardo Estupinian
     */
    public function uploadExcel(Request $request,$id_ciclo){
        $ciclo = Ciclo::where('id_ciclo',$id_ciclo)->first();

        //Se guarda en la ruta storage/app/importExcel de manera temporal y se recupera la ruta
        $ruta=Storage::putFileAs('importExcel',$request->file('archivo'),Carbon::now()->format('His')."Excel.xlsx");

        //Mensaje por defecto
        $message=['error'=>'Hubo un error en la importacion. Verifique que sea el formato adecuado.','type'=>1];

        $spreadsheet=null;
        $data=null;
        try{
            //Se carga el archivo que subio el archivo para poder acceder a los datos
            $spreadsheet = IOFactory::load(storage_path($path = "app\\".str_replace("/","\\",$ruta)));

            //Todas las filas se convierten en un array que puede ser accedido por las letras de las columnas de archivo excel
            $data = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            //dd($data);

        }catch(Exception $e){
            return response()->json($message);
        }
        
        if($data[1]["I"]=="MC-01"){
            for($i = 5; $i<=count($data);$i++){
                if($data[$i]["A"]!=null && $data[$i]["B"]!=null){
                    $materia = Materia::where('codigo_mat', strtoupper($data[$i]["A"]))->first();
                    $docente = Docente::where('carnet_dcn', strtoupper($data[$i]["B"]))->first();

                    if(isset($materia) && isset($docente)){
                        $materia_ciclo = CicloMateria::
                                  where('id_cat_mat',$materia->id_cat_mat)
                                ->where('id_ciclo',$ciclo->id_ciclo)->first();
                        if(isset($materia_ciclo)){
                            $carga = CargaAcademica::
                                  where('id_mat_ci',$materia_ciclo->id_mat_ci)
                                ->where('id_pdg_dcn',$docente->id_pdg_dcn)->first();
                            
                            if(!isset($carga)){
                                $carga = new CargaAcademica;
                                $carga->id_mat_ci = $materia_ciclo->id_mat_ci;
                                $carga->id_pdg_dcn = $docente->id_pdg_dcn;
                                $carga->save();
                            }
                        }else{
                            $materia_ciclo = new  CicloMateria;
                            $materia_ciclo->id_ciclo = $ciclo->id_ciclo;
                            $materia_ciclo->id_cat_mat = $materia->id_cat_mat;
                            $materia_ciclo->save();

                            $carga = new CargaAcademica;
                            $carga->id_mat_ci = $materia_ciclo->id;
                            $carga->id_pdg_dcn = $docente->id_pdg_dcn;
                            $carga->save();
                        }
                    }
                }
            }
            $message=['success'=>'La importacion de materias al ciclo se ejecuto correctamente.','type'=>2];
        }else{
            $message=['error'=>'La plantilla subida no es para agregar Materias al Ciclo.','type'=>1];
        }
        
        //Eliminar el archivo subido, solo se utiliza para la importacion y luego de desecha
        Storage::delete($ruta);

        return response()->json($message);
    }

}
