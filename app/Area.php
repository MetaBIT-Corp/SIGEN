<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table='area';
    
    protected $fillable = [
        'id_cat_mat','id_pdg_dcn','id_tipo_item','titulo',
    ];
    
    /**
	 * Metodo para recuperacion de materia a la que pertenece el area.
	 * @return Objeto materia a la que pertenece el area.
	 */
    public function materia(){
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
