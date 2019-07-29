<?php

use Illuminate\Database\Seeder;
use App\Area;

class AreaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Areas de Matematica
        Area::create([
        	'id_cat_mat'=>1,
        	'id_pdg_dcn'=>1,
        	'id_tipo_item'=>1,
        	'titulo'=>'Razones Trigonometricas'
        ]);
        Area::create([
        	'id_cat_mat'=>1,
        	'id_pdg_dcn'=>1,
        	'id_tipo_item'=>2,
        	'titulo'=>'Resultado de ecuaciones'
        ]);
        Area::create([
        	'id_cat_mat'=>1,
        	'id_pdg_dcn'=>1,
        	'id_tipo_item'=>3,
        	'titulo'=>'Matematicos reconocidos'
        ]);
        Area::create([
        	'id_cat_mat'=>1,
        	'id_pdg_dcn'=>1,
        	'id_tipo_item'=>4,
        	'titulo'=>'Aritmetica'
        ]);

        //Area de Encuesta
        Area::create([
            'id_cat_mat'=>1,
            'id_pdg_dcn'=>1,
            'id_tipo_item'=>1,
            'titulo'=>'Grupos de Discucion'
        ]);
        Area::create([
            'id_cat_mat'=>1,
            'id_pdg_dcn'=>1,
            'id_tipo_item'=>1,
            'titulo'=>'Numero de equipos'
        ]);
    }
}
