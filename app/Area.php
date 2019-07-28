<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    /**
	 * Metodo para recuperacion de materia a la que pertenece el area.
	 * @return Objeto materia a la que pertenece el area.
	 */
    public fuction materia(){
    	return $this->belongsTo('App\Materia');
    }

    /**
	 * Metodo para recuperacion del docente que creo el area.
	 * @return Objeto docente que creo el area.
	 */
    public function docente(){
    	return $this->belongsTo('App\Docente');
    }

    /**
	 * Metodo para recuperacion del tipo de item del area.
	 * @return Objeto tipo_item del area.
	 */
    public function tipo_item(){
    	return $this->belongsTo('App\Tipo_Item');
    }

}
