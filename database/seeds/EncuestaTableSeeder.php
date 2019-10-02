<?php

use Illuminate\Database\Seeder;
use App\Encuesta;
class EncuestaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Encuesta::create([
        	'id_docente'=>1,
        	'titulo_encuesta'=>'Incripcion de Discusion',
        	'descripcion_encuesta'=>'Incripcion en los diferentes grupos de discusion',
        	'fecha_inicio_encuesta'=>'2019-07-28 21:52:27',
        	'fecha_final_encuesta'=>'2019-12-28 22:52:27',
            'visible'=>0
        ]);
        Encuesta::create([
        	'id_docente'=>1,
        	'titulo_encuesta'=>'Rendimiento de docente en clase',
        	'descripcion_encuesta'=>'Los alumnos calificaran a Ricardo',
        	'fecha_inicio_encuesta'=>'2019-07-28 21:52:27',
        	'fecha_final_encuesta'=>'2019-12-28 22:52:27',
            'visible'=>0
        ]);
        Encuesta::create([
        	'id_docente'=>2,
        	'titulo_encuesta'=>'Mejores propuestas en clases',
        	'descripcion_encuesta'=>'Alumnos aceptaran mejoras en clases de Enrique',
        	'fecha_inicio_encuesta'=>'2019-07-28 21:52:27',
        	'fecha_final_encuesta'=>'2019-12-28 22:52:27',
            'visible'=>0
        ]);
    }
}
