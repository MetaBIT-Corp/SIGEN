<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    //
    Protected $table="pdg_dcn_docente"; //tabla vinculada a este modelo
    
    protected $fillable = [
        
    ];

    public function usuario(){
    	return $this->belongsTo('App\User', 'user_id', 'id');
    }

}
