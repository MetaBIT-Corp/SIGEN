<?php

use Illuminate\Database\Seeder;
use App\Ciclo;

class CicloTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Ciclo::create([
        	'num_ciclo'=>1,
        	'anio'=>2018,
        	'estado'=>0
        ]);
         Ciclo::create([
        	'num_ciclo'=>2,
        	'anio'=>2018,
        	'estado'=>0
        ]);
          Ciclo::create([
        	'num_ciclo'=>1,
        	'anio'=>2019,
        	'estado'=>0
        ]);
           Ciclo::create([
        	'num_ciclo'=>2,
        	'anio'=>2019,
        	'estado'=>1
        ]);
    }
}
