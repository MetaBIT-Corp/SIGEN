<?php

use Illuminate\Database\Seeder;
use App\DetalleInscEst;
class DetalleInscripcionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DetalleInscEst::create([
        	'id_carg_aca'=>4,
        	'id_est'=>1
        ]);
        DetalleInscEst::create([
        	'id_carg_aca'=>3,
        	'id_est'=>1
        ]);
        DetalleInscEst::create([
        	'id_carg_aca'=>2,
        	'id_est'=>1
        ]);
        DetalleInscEst::create([
        	'id_carg_aca'=>1,
        	'id_est'=>1
        ]);
        DetalleInscEst::create([
        	'id_carg_aca'=>4,
        	'id_est'=>2
        ]);
        DetalleInscEst::create([
        	'id_carg_aca'=>1,
        	'id_est'=>2
        ]);
        DetalleInscEst::create([
            'id_carg_aca'=>1,
            'id_est'=>3
        ]);
        DetalleInscEst::create([
            'id_carg_aca'=>1,
            'id_est'=>4
        ]);
        DetalleInscEst::create([
            'id_carg_aca'=>1,
            'id_est'=>5
        ]);
    }
}
