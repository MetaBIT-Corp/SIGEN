<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    //
    Protected $table="pdg_dcn_docente"; //tabla vinculada a este modelo
    
    protected $primaryKey ="id_pdg_dcn"; //indica la llave primaria, necesaria para editar

    protected $fillable = [
        'nombre_docente', 'descripcion_docente', 'carnet_dcn','anio_titulo','activo', 'user_id'
    ];

    public function usuario(){
    	return $this->belongsTo('App\User', 'user_id', 'id');
    }

}
