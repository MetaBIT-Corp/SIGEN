<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Encuesta extends Model
{
    protected $table='encuesta';
    
    protected $fillable = [
      'id_docente','titulo_encuesta','descripcion_encuesta','fecha_inicio_encuesta','fecha_final_encuesta',  
    ];

    public function intento(){
    	return $this->belongsTo(Intento::class);
    }
}
