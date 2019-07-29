<?php

use Illuminate\Database\Seeder;
use App\GrupoCarga;
class GrupoCargaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GrupoCarga::create([
        	'tipo'=>'Teorico'
        ]);
        GrupoCarga::create([
        	'tipo'=>'Discusion'
        ]);
        GrupoCarga::create([
        	'tipo'=>'Laboratorio'
        ]);
    }
}
