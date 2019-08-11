<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    //
    Protected $table = "cat_mat_materia";
    
    protected $fillable = [
        'codigo_mat','nombre_mar','es_electiva','maximo_cant_preguntas',
    ];

    /**
     * Metodo para obtener las areas pertenecientes a una materia.
     * @author Ricardo
     * @return Array de objetos de area.
     */
    public function areas(){
    	return $this->hasMany('App\Area','id_cat_mat','id_cat_mat');
    }
}
