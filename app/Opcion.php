<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Opcion extends Model
{
    protected $table='opcion';
    
    protected $fillable = [
        'pregunta_id','opcion','correcta',
    ];
}
