<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Ciclo extends Model
{
    //
    Protected $table="ciclo";
    
    protected $fillable = [
        'num_ciclo','anio','estado','inicio_ciclo','fin_ciclo'
    ];

    /*Funcion para verificar que el ciclo tiene asociadas materias*/
    public function materias_ciclo($id_ciclo){
        $materias = DB::table('materia_ciclo')->where('id_ciclo',$id_ciclo)->first();
        $value = false;
        if(isset($materias)){
            $value = true;
        }
        return $value;
    }
}
