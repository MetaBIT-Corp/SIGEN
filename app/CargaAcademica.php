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
}
