<?php

use Illuminate\Database\Seeder;
use App\Clave_Area;
class ClaveAreaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	/*Son claves areas del area id 1 que pertenece a la materia MAT115
    	En cada area estan conteplados los 4 tipos de items solo con de una clave, si se desea agregar mas claves_areas de otros parciales se deben agregar seeders en area, pregunta y opcion y relacionarlas con las claves que estan creadas.*/
        Clave_Area::create([
        	'id_area'=>1,//Opcion Multiple
        	'id_clave'=>1,
        	'numero_preguntas'=>2,
        	'aleatorio'=>false,
        	'peso'=>25
        ]);
        Clave_Area::create([
        	'id_area'=>2,//Verdadero Falso
        	'id_clave'=>1,
        	'numero_preguntas'=>1,
        	'aleatorio'=>true,
        	'peso'=>25
        ]);
        Clave_Area::create([
        	'id_area'=>3,//Emparejamiento
        	'id_clave'=>1,
        	'numero_preguntas'=>3,
        	'aleatorio'=>true,
        	'peso'=>25
        ]);
        Clave_Area::create([
        	'id_area'=>4,//Texto Corto
        	'id_clave'=>1,
        	'numero_preguntas'=>2,
        	'aleatorio'=>true,
        	'peso'=>25
        ]);


        //Para encuesta con clave id 7
        Clave_Area::create([
        	'id_area'=>5,//Opcion Multiple
        	'id_clave'=>7,
        	'numero_preguntas'=>1,
        	'aleatorio'=>true,
        	'peso'=>25
        ]);
        Clave_Area::create([
        	'id_area'=>6,//Opcion Multiple
        	'id_clave'=>7,
        	'numero_preguntas'=>1,
        	'aleatorio'=>true,
        	'peso'=>25
        ]);
    }
}
