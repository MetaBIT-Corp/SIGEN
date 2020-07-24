<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CargaAcademica extends Model
{ 
    //
    Protected $table="carga_academica";


    protected $fillable = [
        'id_mat_ci','id_grup_carg','id_pdg_dcn',
    ];

    public function docente(){
    	return $this->belongsTo(Docente::class, 'id_pdg_dcn', 'id_pdg_dcn');
    }

    public function materiaCiclo(){
    	return $this->belongsTo(CicloMateria::class, 'id_mat_ci', 'id_mat_ci');
    }
}
