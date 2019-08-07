<?php

use Illuminate\Database\Seeder;
use App\Evaluacion;
class EvaluacionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	//Evaluaciones de docente Ricardo y materia MAT115
        Evaluacion::create([
        	'id_carga'=>1,
        	'duracion'=>100,
        	'intentos'=>2,
        	'nombre_evaluacion'=>'Preparcial I',
        	'descripcion_evaluacion'=>'Preparacion para parcial de Mate',
        	'preguntas_a_mostrar'=>1
        ]);
        Evaluacion::create([
        	'id_carga'=>1,
        	'duracion'=>100,
        	'intentos'=>2,
        	'nombre_evaluacion'=>'Parcial I',
        	'descripcion_evaluacion'=>'Se evalua conocimiento basico de matematica',
        	'preguntas_a_mostrar'=>2
        ]);

        //Evaluacion de docente Ricardo y materia HDP115
        Evaluacion::create([
        	'id_carga'=>3,
        	'duracion'=>100,
        	'intentos'=>2,
        	'nombre_evaluacion'=>'Parcial I',
        	'descripcion_evaluacion'=>'Parcial de la unidad herramientas de productividad en SCRUM',
        	'preguntas_a_mostrar'=>3
        ]);

    }
}
