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

    //obtiene el docente a quién pertene esta encuesta. primer paramentro es el modelo padre, el segundo es el atributo en encuesta que hace referencia al docente y el tercero es el atributo llave del docente 
    public function docente(){
    	return $this->belongsTo(Docente::class,'id_docente', 'id_pdg_dcn');

    }

    public function claves(){
        return $this->hasMany(Clave::class);
    }
}
