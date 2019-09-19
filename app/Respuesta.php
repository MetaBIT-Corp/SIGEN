<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Respuesta extends Model
{
    protected $table='respuesta';
    
    protected $fillable = [

    ];

    public function opcion(){
    	return $this->belongsTo(Opcion::class, 'id_opcion', 'id');
    }

    public function pregunta(){
    	return $this->belongsTo(Pregunta::class, 'id_pregunta', 'id');
    }
}
