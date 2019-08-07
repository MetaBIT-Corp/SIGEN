<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Intento extends Model
{
    protected $table='intento';
    
    protected $fillable = [
    	'estudiante_id','clave_id','fecha_inicio_intento','fecha_final_intento','nota_intento'
    ];
}
