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
            'clave_area_id'=>1,
            'pregunta_id'=>1
        ]);
        Clave_Area_Pregunta::create([
            'clave_area_id'=>1,
            'pregunta_id'=>2
        ]);
        Clave_Area_Pregunta::create([
            'clave_area_id'=>2,
            'pregunta_id'=>3
        ]);
        Clave_Area_Pregunta::create([
            'clave_area_id'=>3,
            'pregunta_id'=>7
        ]);
        Clave_Area_Pregunta::create([
            'clave_area_id'=>3,
            'pregunta_id'=>8
        ]);
        Clave_Area_Pregunta::create([
            'clave_area_id'=>3,
            'pregunta_id'=>9
        ]);
        Clave_Area_Pregunta::create([
            'clave_area_id'=>4,
            'pregunta_id'=>5
        ]);
        Clave_Area_Pregunta::create([
            'clave_area_id'=>4,
            'pregunta_id'=>6
        ]);


        //Clave area pregunta para encuestas
        Clave_Area_Pregunta::create([
            'clave_area_id'=>5,
            'pregunta_id'=>11
        ]);
        Clave_Area_Pregunta::create([
            'clave_area_id'=>6,
            'pregunta_id'=>12
        ]);
    }
}
