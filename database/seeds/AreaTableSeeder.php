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
        	'tipo_item_id'=>1,
        	'titulo'=>'Razones Trigonometricas'
        ]);
        Area::create([
        	'id_cat_mat'=>1,
        	'id_pdg_dcn'=>1,
        	'tipo_item_id'=>2,
        	'titulo'=>'Resultado de ecuaciones'
        ]);
        Area::create([
        	'id_cat_mat'=>1,
        	'id_pdg_dcn'=>1,
        	'tipo_item_id'=>3,
        	'titulo'=>'Matematicos reconocidos'
        ]);
        Area::create([
        	'id_cat_mat'=>1,
        	'id_pdg_dcn'=>1,
        	'tipo_item_id'=>4,
        	'titulo'=>'Aritmetica'
        ]);

        //Area de Encuesta
        Area::create([
            'id_cat_mat'=>1,
            'id_pdg_dcn'=>1,
            'tipo_item_id'=>1,
            'titulo'=>'Grupos de Discucion'
        ]);
        Area::create([
            'id_cat_mat'=>1,
            'id_pdg_dcn'=>1,
            'tipo_item_id'=>1,
            'titulo'=>'Numero de equipos'
        ]);

        /*Areas de Prueba (René).789*/
        Area::create([
            'id_cat_mat'=>1,
            'id_pdg_dcn'=>1,
            'tipo_item_id'=>1,
            'titulo'=>'Área de prueba de Opción Múltiple'
        ]);
        Area::create([
            'id_cat_mat'=>1,
            'id_pdg_dcn'=>1,
            'tipo_item_id'=>2,
            'titulo'=>'Área de prueba de Verdadero/Falso'
        ]);
        Area::create([
            'id_cat_mat'=>1,
            'id_pdg_dcn'=>1,
            'tipo_item_id'=>4,
            'titulo'=>'Área de prueba de Respuesta Corta'
        ]);
    }
}
