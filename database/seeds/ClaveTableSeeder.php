<?php

use Illuminate\Database\Seeder;
use App\Clave;
class ClaveTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	//Claves de evaluacion de MAT115 con id 1 Turno id 1
        /*Clave::create([
        	'turno_id'=>1,
        	'numero_clave'=>1,
        ]);
        Clave::create([
        	'turno_id'=>1,
        	'numero_clave'=>2,
        ]);
        Clave::create([
        	'turno_id'=>2,
        	'numero_clave'=>1,
        ]);
        Clave::create([
        	'turno_id'=>3,
        	'numero_clave'=>1,
        ]);

        //Clave de evaluaciones de HDP115 con id 3 turno id 
        Clave::create([
        	'turno_id'=>4,
        	'numero_clave'=>1,
        ]);
        Clave::create([
        	'turno_id'=>5,
        	'numero_clave'=>1,
        ]);

        //Claves para encuestas
        Clave::create([
        	'encuesta_id'=>1,
        	'numero_clave'=>1,
        ]);
        Clave::create([
        	'encuesta_id'=>2,
        	'numero_clave'=>1,
        ]);
        Clave::create([
        	'encuesta_id'=>3,
        	'numero_clave'=>1,
        ]);

        /*Clave de Prueba (René).
        Clave::create([
            'turno_id'=>6,
            'numero_clave'=>1,
        ]);*/

    }
}
