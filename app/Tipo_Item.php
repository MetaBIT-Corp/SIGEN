<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tipo_Item extends Model
{
	protected $table='tipo_item';
    
    protected $fillable = [
        'nombre_tipo_item',
    ];
}
