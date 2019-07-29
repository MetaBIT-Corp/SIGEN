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
        Clave::create([
        	'id_turno'=>1,
        	'numero_clave'=>1,
        ]);
        Clave::create([
        	'id_turno'=>1,
        	'numero_clave'=>2,
        ]);
        Clave::create([
        	'id_turno'=>2,
        	'numero_clave'=>1,
        ]);
        Clave::create([
        	'id_turno'=>3,
        	'numero_clave'=>1,
        ]);

        //Clave de evaluaciones de HDP115 con id 3 turno id 
        Clave::create([
        	'id_turno'=>4,
        	'numero_clave'=>1,
        ]);
        Clave::create([
        	'id_turno'=>5,
        	'numero_clave'=>1,
        ]);

        //Claves para encuestas
        Clave::create([
        	'id_encuesta'=>1,
        	'numero_clave'=>1,
        ]);
        Clave::create([
        	'id_encuesta'=>2,
        	'numero_clave'=>1,
        ]);
        Clave::create([
        	'id_encuesta'=>3,
        	'numero_clave'=>1,
        ]);
    }
}
