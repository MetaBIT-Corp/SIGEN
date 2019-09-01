<?php

use Illuminate\Database\Seeder;
use App\CicloMateria;
class MateriaCicloTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CicloMateria::create([
        	'id_cat_mat'=>1,
        	'id_ciclo'=>4
        ]);
        CicloMateria::create([
        	'id_cat_mat'=>4,
        	'id_ciclo'=>4
        ]);
        CicloMateria::create([
        	'id_cat_mat'=>2,
        	'id_ciclo'=>4
        ]);
        CicloMateria::create([
        	'id_cat_mat'=>3,
        	'id_ciclo'=>2
        ]);
        CicloMateria::create([
        	'id_cat_mat'=>5,
        	'id_ciclo'=>3
        ]);
        CicloMateria::create([
        	'id_cat_mat'=>6,
        	'id_ciclo'=>3
        ]);

        /*MateriaCiclo de Prueba (René).*/
        CicloMateria::create([
            'id_cat_mat'=>7,
            'id_ciclo'=>4
        ]);
    }
}
