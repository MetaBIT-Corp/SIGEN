<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clave extends Model
{
    protected $table='clave';
    
    protected $fillable = [
        'id_turno','numero_clave',
    ];
}
