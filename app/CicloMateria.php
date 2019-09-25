<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CicloMateria extends Model
{
    //
    Protected $table = "materia_ciclo";
    
    protected $fillable = [
        'id_cat_mat','id_ciclo',
    ];
    public function cargas(){
    	return $this->hasMany(CargaAcademica::class, 'id_mat_ci', 'id_mat_ci');
    }
}
