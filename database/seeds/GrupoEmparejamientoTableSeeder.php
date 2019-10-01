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
        //Emparejamiento
        Grupo_Emparejamiento::create([
        	'area_id'=>3,
        	'descripcion_grupo_emp'=>'Matematicas Reconocidas'
        ]);
        Grupo_Emparejamiento::create([
        	'area_id'=>3,
        	'descripcion_grupo_emp'=>'Matematicos Fracasados'
        ]);
        
        //Opción múltiple
        Grupo_Emparejamiento::create([
            'area_id'=>1,
        ]);
        Grupo_Emparejamiento::create([
            'area_id'=>1,
        ]);
        Grupo_Emparejamiento::create([
            'area_id'=>1,
        ]);
        Grupo_Emparejamiento::create([
            'area_id'=>1,
        ]);
        Grupo_Emparejamiento::create([
            'area_id'=>1,
        ]);
        Grupo_Emparejamiento::create([
            'area_id'=>1,
        ]);
        Grupo_Emparejamiento::create([
            'area_id'=>1,
        ]);
        Grupo_Emparejamiento::create([
            'area_id'=>1,
        ]);
        Grupo_Emparejamiento::create([
            'area_id'=>1,
        ]);
        Grupo_Emparejamiento::create([
            'area_id'=>1,
        ]);

        //Opción múltiple
        Grupo_Emparejamiento::create([
            'area_id'=>2,
        ]);
        Grupo_Emparejamiento::create([
            'area_id'=>2,
        ]);
        Grupo_Emparejamiento::create([
            'area_id'=>2,
        ]);
        Grupo_Emparejamiento::create([
            'area_id'=>2,
        ]);
        Grupo_Emparejamiento::create([
            'area_id'=>2,
        ]);
        Grupo_Emparejamiento::create([
            'area_id'=>2,
        ]);
        Grupo_Emparejamiento::create([
            'area_id'=>2,
        ]);
        Grupo_Emparejamiento::create([
            'area_id'=>2,
        ]);
        Grupo_Emparejamiento::create([
            'area_id'=>2,
        ]);
        Grupo_Emparejamiento::create([
            'area_id'=>2,
        ]);

        //Respuesta corta
        Grupo_Emparejamiento::create([
            'area_id'=>4,
        ]);
        Grupo_Emparejamiento::create([
            'area_id'=>4,
        ]);
        Grupo_Emparejamiento::create([
            'area_id'=>4,
        ]);
        Grupo_Emparejamiento::create([
            'area_id'=>4,
        ]);
        Grupo_Emparejamiento::create([
            'area_id'=>4,
        ]);
        
    }
}
