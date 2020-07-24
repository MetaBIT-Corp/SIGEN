<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\CicloMateria;
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
        $ruta='plantillaExcel/materia-ciclo.xlsx';
        return Storage::download($ruta,$nombre_descarga);
    }

}
