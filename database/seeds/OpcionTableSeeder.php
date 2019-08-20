<?php

use Illuminate\Database\Seeder;
use App\Opcion;
class OpcionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	//Pregunta id 1 de opcion multiple 
        Opcion::create([
        	'pregunta_id'=>1,
        	'opcion'=>'10',
        	'correcta'=>true
        ]);
        Opcion::create([
        	'pregunta_id'=>1,
        	'opcion'=>'15',
        	'correcta'=>false
        ]);
        Opcion::create([
        	'pregunta_id'=>1,
        	'opcion'=>'x+2',
        	'correcta'=>false
        ]);
        Opcion::create([
        	'pregunta_id'=>1,
        	'opcion'=>'9+1-2',
        	'correcta'=>false
        ]);

        //Pregunta id 2 de opcion multiple
        Opcion::create([
        	'pregunta_id'=>2,
        	'opcion'=>'800-300',
        	'correcta'=>true
        ]);
        Opcion::create([
        	'pregunta_id'=>2,
        	'opcion'=>'5000',
        	'correcta'=>false
        ]);
        Opcion::create([
        	'pregunta_id'=>2,
        	'opcion'=>'501',
        	'correcta'=>false
        ]);
        Opcion::create([
        	'pregunta_id'=>2,
        	'opcion'=>'y+(z^2)',
        	'correcta'=>false
        ]);

        //Pregunta id 3 y 4 de Verdadero/falso
        Opcion::create([
        	'pregunta_id'=>3,
        	'opcion'=>'Falso',
        	'correcta'=>false
        ]);
        Opcion::create([
        	'pregunta_id'=>4,
        	'opcion'=>'Verdadero',
        	'correcta'=>true
        ]);

        //Pregunta id 5 y 6 texto corto
        Opcion::create([
            'pregunta_id'=>5,
            'opcion'=>'newtom',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>6,
            'opcion'=>'4',
            'correcta'=>true
        ]);

        //Emparejamiento
        Opcion::create([
        	'pregunta_id'=>7,
        	'opcion'=>'Sigma Plus',
        	'correcta'=>true
        ]);
        Opcion::create([
        	'pregunta_id'=>8,
        	'opcion'=>'Pigma Pix',
        	'correcta'=>true
        ]);
        Opcion::create([
        	'pregunta_id'=>9,
        	'opcion'=>'Peter Eueler Fracas',
        	'correcta'=>true
        ]);
        Opcion::create([
        	'pregunta_id'=>10,
        	'opcion'=>'Arquimedes',
        	'correcta'=>true
        ]);

        //Opciones para preguntas de Encuesta
        Opcion::create([
            'pregunta_id'=>11,
            'opcion'=>'GL20',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>11,
            'opcion'=>'GL25',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>11,
            'opcion'=>'GL23',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>12,
            'opcion'=>'UNO',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>12,
            'opcion'=>'OCHO',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>12,
            'opcion'=>'TRECE',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>12,
            'opcion'=>'VEINTE',
            'correcta'=>true
        ]);

        /*Opciones de Prueba (René).*/

        /*Opciones para Opción Múltiple.*/
        Opcion::create([
            'pregunta_id'=>13,
            'opcion'=>'Si',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>13,
            'opcion'=>'No',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>13,
            'opcion'=>'Quizá',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>13,
            'opcion'=>'Definitivamente Talvez',
            'correcta'=>false
        ]);

        /*Opciones para Verdadero/Falso.*/
        Opcion::create([
            'pregunta_id'=>14,
            'opcion'=>'Verdadero',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>14,
            'opcion'=>'Falso',
            'correcta'=>false
        ]);

        /*Opciones para Respuesta Corta*/
        Opcion::create([
            'pregunta_id'=>15,
            'opcion'=>'Si',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>15,
            'opcion'=>'Verdadero',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>15,
            'opcion'=>'verdadero',
            'correcta'=>true
        ]);
    }
}
