<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Opcion extends Model
{
    protected $table='opcion';
    
    protected $fillable = [
        'id_pregunta','opcion','correcta',
    ];
}
