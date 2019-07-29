<?php

use Illuminate\Database\Seeder;
use App\Turno;
class TurnoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	//Turnos de evaluacion id 1 de docente Ricardo y materia MAT115
        Turno::create([
        	'id_evaluacion'=>1,
        	'fecha_inicio_turno'=>'2019-07-28 21:52:28',
        	'fecha_final_turno'=>'2019-07-28 22:52:28',
        	'visibilidad'=>true,
        	'contraseña'=>bcrypt('ues')
        ]);
        Turno::create([
        	'id_evaluacion'=>1,
        	'fecha_inicio_turno'=>'2019-07-28 22:59:28',
        	'fecha_final_turno'=>'2019-07-28 23:59:28',
        	'visibilidad'=>true,
        	'contraseña'=>bcrypt('ues')
        ]);

        //Turnos de evaluacion id 2 de docente Ricardo y materia MAT115
        Turno::create([
        	'id_evaluacion'=>2,
        	'fecha_inicio_turno'=>'2019-07-28 21:52:28',
        	'fecha_final_turno'=>'2019-07-28 21:52:28',
        	'visibilidad'=>true,
        	'contraseña'=>bcrypt('ues')
        ]);

        //Turnos de evaluacion id 3 de de docente Ricardo y materia HDP115
        Turno::create([
        	'id_evaluacion'=>3,
        	'fecha_inicio_turno'=>'2019-07-28 21:52:28',
        	'fecha_final_turno'=>'2019-07-28 21:52:28',
        	'visibilidad'=>true,
        	'contraseña'=>bcrypt('ues')
        ]);
        Turno::create([
        	'id_evaluacion'=>3,
        	'fecha_inicio_turno'=>'2019-07-29 21:52:28',
        	'fecha_final_turno'=>'2019-07-29 22:52:28',
        	'visibilidad'=>true,
        	'contraseña'=>bcrypt('ues')
        ]);
    }
}
