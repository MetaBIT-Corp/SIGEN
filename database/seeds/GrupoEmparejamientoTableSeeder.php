<?php

use Illuminate\Database\Seeder;
use App\Grupo_Emparejamiento;
class GrupoEmparejamientoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Grupo_Emparejamiento::create([
        	'area_id'=>3,
        	'descripcion_grupo_emp'=>'Matematicas Reconocidas'
        ]);
        Grupo_Emparejamiento::create([
        	'area_id'=>3,
        	'descripcion_grupo_emp'=>'Matematicos Fracasados'
        ]);
    }
}
