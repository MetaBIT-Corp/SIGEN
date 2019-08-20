<?php

use Illuminate\Database\Seeder;
use App\Pregunta;
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
    	Pregunta::create([
        	'pregunta'=>'Es el resultado de 5+5:'//Opcion Multiple
        ]);
        Pregunta::create([
        	'pregunta'=>'5*100 es:'//Opcion Multiple
        ]);
        Pregunta::create([
        	'pregunta'=>'Pi es 4.56897235'//Falso/Verdadero
        ]);
        Pregunta::create([
        	'pregunta'=>'La derivada de una constante es cero'//Falso/Verdadero
        ]);
         Pregunta::create([
            'pregunta'=>'¿Como se le llama a esa notacion de la derivada D\'?'//Texto Corto
        ]);
          Pregunta::create([
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
        	'grupo_emparejamiento_id'=>2,
        	'pregunta'=>'¿Quien intento crear el numero Euler?'
        ]);
        Pregunta::create([
        	'grupo_emparejamiento_id'=>2,
        	'pregunta'=>'Le dicen Big Arqui'
        ]);

        //Preguntas de opcion multiple para encuesta
        Pregunta::create([
            'grupo_emparejamiento_id'=>2,
            'pregunta'=>'¿Que grupo de lab?'
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>2,
            'pregunta'=>'¿Que numero de equipo desea?'
        ]);

        /*Preguntas de Prueba (René)*/
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


    }
}
