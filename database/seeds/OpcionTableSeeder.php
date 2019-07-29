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
        	'id_pregunta'=>1,
        	'opcion'=>'10',
        	'correcta'=>true
        ]);
        Opcion::create([
        	'id_pregunta'=>1,
        	'opcion'=>'15',
        	'correcta'=>false
        ]);
        Opcion::create([
        	'id_pregunta'=>1,
        	'opcion'=>'x+2',
        	'correcta'=>false
        ]);
        Opcion::create([
        	'id_pregunta'=>1,
        	'opcion'=>'9+1-2',
        	'correcta'=>false
        ]);

        //Pregunta id 2 de opcion multiple
        Opcion::create([
        	'id_pregunta'=>2,
        	'opcion'=>'800-300',
        	'correcta'=>true
        ]);
        Opcion::create([
        	'id_pregunta'=>2,
        	'opcion'=>'5000',
        	'correcta'=>false
        ]);
        Opcion::create([
        	'id_pregunta'=>2,
        	'opcion'=>'501',
        	'correcta'=>false
        ]);
        Opcion::create([
        	'id_pregunta'=>2,
        	'opcion'=>'y+(z^2)',
        	'correcta'=>false
        ]);

        //Pregunta id 3 y 4 de Verdadero/falso
        Opcion::create([
        	'id_pregunta'=>3,
        	'opcion'=>'Falso',
        	'correcta'=>false
        ]);
        Opcion::create([
        	'id_pregunta'=>4,
        	'opcion'=>'Verdadero',
        	'correcta'=>true
        ]);

        //Pregunta id 5 y 6 texto corto
        Opcion::create([
            'id_pregunta'=>5,
            'opcion'=>'newtom',
            'correcta'=>true
        ]);
        Opcion::create([
            'id_pregunta'=>6,
            'opcion'=>'4',
            'correcta'=>true
        ]);

        //Emparejamiento
        Opcion::create([
        	'id_pregunta'=>7,
        	'opcion'=>'Sigma Plus',
        	'correcta'=>true
        ]);
        Opcion::create([
        	'id_pregunta'=>8,
        	'opcion'=>'Pigma Pix',
        	'correcta'=>true
        ]);
        Opcion::create([
        	'id_pregunta'=>9,
        	'opcion'=>'Peter Eueler Fracas',
        	'correcta'=>true
        ]);
        Opcion::create([
        	'id_pregunta'=>10,
        	'opcion'=>'Arquimedes',
        	'correcta'=>true
        ]);

        //Opciones para preguntas de Encuesta
        Opcion::create([
            'id_pregunta'=>11,
            'opcion'=>'GL20',
            'correcta'=>true
        ]);
        Opcion::create([
            'id_pregunta'=>11,
            'opcion'=>'GL25',
            'correcta'=>true
        ]);
        Opcion::create([
            'id_pregunta'=>11,
            'opcion'=>'GL23',
            'correcta'=>true
        ]);
        Opcion::create([
            'id_pregunta'=>12,
            'opcion'=>'UNO',
            'correcta'=>true
        ]);
        Opcion::create([
            'id_pregunta'=>12,
            'opcion'=>'OCHO',
            'correcta'=>true
        ]);
        Opcion::create([
            'id_pregunta'=>12,
            'opcion'=>'TRECE',
            'correcta'=>true
        ]);
        Opcion::create([
            'id_pregunta'=>12,
            'opcion'=>'VEINTE',
            'correcta'=>true
        ]);
    }
}
