<?php

use Illuminate\Database\Seeder;
use App\Pregunta;
use App\Grupo_Emparejamiento;
class PreguntaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	//Preguntas variadas
        Grupo_Emparejamiento::create([
            'area_id'=>1
        ]);
    	Pregunta::create([
            'grupo_emparejamiento_id'=>7,
        	'pregunta'=>'Es el resultado de 5+5:'//Opcion Multiple
        ]);


        Grupo_Emparejamiento::create([
            'area_id'=>1
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>8,
        	'pregunta'=>'5*100 es:'//Opcion Multiple
        ]);


        Grupo_Emparejamiento::create([
            'area_id'=>2
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>9,
        	'pregunta'=>'Pi es 4.56897235'//Falso/Verdadero
        ]);


        Grupo_Emparejamiento::create([
            'area_id'=>2
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>10,
        	'pregunta'=>'La derivada de una constante es cero'//Falso/Verdadero
        ]);


        Grupo_Emparejamiento::create([
            'area_id'=>4
        ]);
         Pregunta::create([
            'grupo_emparejamiento_id'=>11,
            'pregunta'=>'¿Como se le llama a esa notacion de la derivada D\'?'//Texto Corto
        ]);

        Grupo_Emparejamiento::create([
            'area_id'=>4
        ]);

        Pregunta::create([
            'grupo_emparejamiento_id'=>12,
            'pregunta'=>'Escriba cuanto es 2+2:'//Texto Corto
        ]);


    	//Preguntas que perteneces a la modalidad de emparejamiento
        Pregunta::create([
        	'grupo_emparejamiento_id'=>1,
        	'pregunta'=>'¿Quien es la mujer que creo el signo +?'
        ]);
        Pregunta::create([
        	'grupo_emparejamiento_id'=>1,
        	'pregunta'=>'¿Quien fue el hombre que creo el numero pi?'
        ]);
        Pregunta::create([
        	'grupo_emparejamiento_id'=>1,
        	'pregunta'=>'¿Quien intento crear el numero Euler?'
        ]);
        Pregunta::create([
        	'grupo_emparejamiento_id'=>1,
        	'pregunta'=>'Le dicen Big Arqui'
        ]);

        //Preguntas de Emparejamiento para encuesta
        Pregunta::create([
            'grupo_emparejamiento_id'=>2,
            'pregunta'=>'¿Que grupo de lab?'
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>2,
            'pregunta'=>'¿Que numero de equipo desea?'
        ]);

        /*Preguntas de Prueba (René).*/
        Pregunta::create([
            'grupo_emparejamiento_id'=>3,
            'pregunta'=>'¿Soy una pregunta de Prueba para Opción Múltiple?'
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>4,
            'pregunta'=>'¿Soy una pregunta de prueba para Verdadero/Falso?'
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>5,
            'pregunta'=>'¿Soy una pregunta de Prueba para Respuestas Cortas?'
        ]);

        /*Preguntas para Grupo Emparejamiento (René).*/

        Pregunta::create([
            'grupo_emparejamiento_id'=>6,
            'pregunta'=>'Barco'
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>6,
            'pregunta'=>'Avión'
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>6,
            'pregunta'=>'Bicicleta'
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>6,
            'pregunta'=>'Submarino'
        ]);


    }
}
