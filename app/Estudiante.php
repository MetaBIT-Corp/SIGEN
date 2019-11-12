<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    //
    Protected $table="estudiante";
    
    protected $fillable = [
    	'user_id',
    ];

    public function usuario(){
    	return $this->belongsTo('App\User');
    }
}
