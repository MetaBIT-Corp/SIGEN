<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table='area';
    
    /**
	 * Metodo para recuperacion de materia a la que pertenece el area.
     * @author Ricardo Estupinian
	 * @return Objeto materia a la que pertenece el area.
	 */
    public function materia(){
    	return $this->belongsTo('App\Materia','id_cat_mat','id_cat_mat');
    }

    /**
	 * Metodo para recuperacion del docente que creo el area.
     * @author Ricardo Estupinian
	 * @return Objeto docente que creo el area.
	 */
    public function docente(){
    	return $this->belongsTo('App\Docente','id_pdg_dcn','id_pdg_dcn');
    }

    /**
	 * Metodo para recuperacion del tipo de item del area.
     * @author Ricardo Estupinian
	 * @return Objeto tipo_item del area.
	 */
    public function tipo_item(){
    	return $this->belongsTo('App\Tipo_Item','id_tipo_item');
    }

}
