<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ciclo extends Model
{
    //
    Protected $table="ciclo";
    
    protected $fillable = [
        'num_ciclo','anio','estado',
    ];
}
