<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleInscEst extends Model
{
    //
    Protected $table = "detalle_insc_est";
    
    protected $fillable = [
        'id_carg_aca','id_est',
    ];
}
