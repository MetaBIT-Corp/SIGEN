<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Intento extends Model
{
    protected $table='intento';
    
    protected $fillable = [
    	'estudiante_id','clave_id','fecha_inicio_intento','fecha_final_intento','nota_intento'
    ];

    //Relaciones

    //Un intento tiene muchas respuestas
    public function respuestas(){
    	return $this->hasMany(Respuesta::class, 'id_intento', 'id');
    }

    public function estudiante(){
    	return $this->belongsTo(Estudiante::class, 'estudiante_id', 'id_est');
    }
}
