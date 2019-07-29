<?php

use Illuminate\Database\Seeder;
use App\Tipo_Item;

class TipoItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Tipo de items que existen para cada area
        Tipo_Item::create([
        	'nombre_tipo_item'=>'Opcion multiple'
        ]);

        Tipo_Item::create([
        	'nombre_tipo_item'=>'Falso/Verdadero'
        ]);

        Tipo_Item::create([
        	'nombre_tipo_item'=>'Emparejamiento'
        ]);

        Tipo_Item::create([
        	'nombre_tipo_item'=>'Texto corto'
        ]);
    }
}
