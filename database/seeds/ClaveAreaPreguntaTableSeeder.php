<?php

use Illuminate\Database\Seeder;
use App\Clave_Area_Pregunta;
class ClaveAreaPreguntaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Clave_Area_Pregunta::create([
        	'id_clave_area'=>1,
        	'id_pregunta'=>1
        ]);
        Clave_Area_Pregunta::create([
        	'id_clave_area'=>1,
        	'id_pregunta'=>2
        ]);
        Clave_Area_Pregunta::create([
        	'id_clave_area'=>2,
        	'id_pregunta'=>3
        ]);
        Clave_Area_Pregunta::create([
        	'id_clave_area'=>3,
        	'id_pregunta'=>7
        ]);
        Clave_Area_Pregunta::create([
        	'id_clave_area'=>3,
        	'id_pregunta'=>8
        ]);
        Clave_Area_Pregunta::create([
        	'id_clave_area'=>3,
        	'id_pregunta'=>9
        ]);
        Clave_Area_Pregunta::create([
        	'id_clave_area'=>4,
        	'id_pregunta'=>5
        ]);
        Clave_Area_Pregunta::create([
        	'id_clave_area'=>4,
        	'id_pregunta'=>6
        ]);


        //Clave area pregunta para encuestas
        Clave_Area_Pregunta::create([
        	'id_clave_area'=>5,
        	'id_pregunta'=>11
        ]);
        Clave_Area_Pregunta::create([
        	'id_clave_area'=>6,
        	'id_pregunta'=>12
        ]);
    }
}
