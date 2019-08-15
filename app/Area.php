<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{   
    use SoftDeletes;
    
    protected $table='area';
    
    protected $fillable = [
        'id_cat_mat','id_pdg_dcn','tipo_item_id','titulo',
    ];
    
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
    	return $this->belongsTo('App\Tipo_Item');
    }

    //Recupera los grupos de emparejamiento que pertenecen al área
    public function grupos_emparejamiento(){
        return $this->hasMany(Grupo_Emparejamiento::class);
    }
}
