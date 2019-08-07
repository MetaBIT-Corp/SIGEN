<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CargaAcademica extends Model
{
    //
    Protected $table="carga_academica";

    public function docente(){
    	return $this->belongsTo(Docente::class, 'id_pdg_dcn', 'id_pdg_dcn');
    }
}
