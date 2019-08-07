<?php

use Illuminate\Database\Seeder;
use App\Materia;

class MateriaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Materia::create([
    		'codigo_mat'=>'MAT115',
    		'nombre_mar'=>'Matematica I',
    		'es_electiva'=>0,
    		'maximo_cant_preguntas'=>10
    	]);
    	Materia::create([
    		'codigo_mat'=>'IGF115',
    		'nombre_mar'=>'Ingenieria de Software',
    		'es_electiva'=>1,
    		'maximo_cant_preguntas'=>10
    	]);
    	Materia::create([
    		'codigo_mat'=>'HDP115',
    		'nombre_mar'=>'Herramientas de productividad',
    		'es_electiva'=>0,
    		'maximo_cant_preguntas'=>10
    	]);
    	Materia::create([
    		'codigo_mat'=>'SGG115',
    		'nombre_mar'=>'Sistemas de informacion geograficos',
    		'es_electiva'=>1,
    		'maximo_cant_preguntas'=>10
    	]);
    	Materia::create([
    		'codigo_mat'=>'IAI115',
    		'nombre_mar'=>'Introduccion a la informatica',
    		'es_electiva'=>0,
    		'maximo_cant_preguntas'=>10
    	]);
    	Materia::create([
    		'codigo_mat'=>'MAT215',
    		'nombre_mar'=>'Matematica II',
    		'es_electiva'=>0,
    		'maximo_cant_preguntas'=>10
    	]);
    }
}
