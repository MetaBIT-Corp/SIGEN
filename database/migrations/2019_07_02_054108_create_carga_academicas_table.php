<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCargaAcademicasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carga_academica', function (Blueprint $table) {
            $table->increments('id_carg_aca');

            $table->integer('id_mat_ci');
            //$table->foreign('id_mat_ci')->references('id_mat_ci')->on('ciclo_materia');
            $table->integer('id_pdg_dcn');
            //$table->foreign('id_pdg_dcn')->references('id_pdg_dcn')->on('pgd_dcn_docente');


            $table->integer('id_grup_carg');
            //$table->foreign('id_grup_carg')->references('id_grup_carg')->on('grupo_carga');


           


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carga_academica');
    }
}
