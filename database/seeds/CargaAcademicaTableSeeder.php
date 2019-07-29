<?php

use Illuminate\Database\Seeder;
use App\CargaAcademica;
class CargaAcademicaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CargaAcademica::create([
        	'id_mat_ci'=>1,
        	'id_grup_carg'=>1,
        	'id_pdg_dcn'=>1
        ]);
        CargaAcademica::create([
        	'id_mat_ci'=>3,
        	'id_grup_carg'=>1,
        	'id_pdg_dcn'=>2
        ]);
        CargaAcademica::create([
        	'id_mat_ci'=>4,
        	'id_grup_carg'=>1,
        	'id_pdg_dcn'=>1
        ]);
        CargaAcademica::create([
        	'id_mat_ci'=>2,
        	'id_grup_carg'=>1,
        	'id_pdg_dcn'=>3
        ]);
    }
}
