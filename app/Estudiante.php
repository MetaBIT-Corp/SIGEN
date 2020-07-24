<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    //
    Protected $table="estudiante";
        
    protected $primaryKey ="id_est"; //indica la llave primaria, necesaria para editar
    
    protected $fillable = [
    	'user_id', 'nombre', 'carnet', 'anio_ingreso', 'aactivo'
    ];

    public function usuario(){
    	return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
