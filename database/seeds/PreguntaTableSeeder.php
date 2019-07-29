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
        	'id_grupo_emp'=>1,
        	'pregunta'=>'¿Quien es la mujer que creo el signo +?'
        ]);
        Pregunta::create([
        	'id_grupo_emp'=>1,
        	'pregunta'=>'¿Quien fue el hombre que creo el numero pi?'
        ]);
        Pregunta::create([
        	'id_grupo_emp'=>2,
        	'pregunta'=>'¿Quien intento crear el numero Euler?'
        ]);
        Pregunta::create([
        	'id_grupo_emp'=>2,
        	'pregunta'=>'Le dicen Big Arqui'
        ]);

        //Preguntas de opcion multiple para encuesta
        Pregunta::create([
            'id_grupo_emp'=>2,
            'pregunta'=>'¿Que grupo de lab?'
        ]);
        Pregunta::create([
            'id_grupo_emp'=>2,
            'pregunta'=>'¿Que numero de equipo desea?'
        ]);


    }
}
