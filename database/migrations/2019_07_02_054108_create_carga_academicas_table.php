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

            $table->integer('id_mat_ci')->unsigned();
            $table->foreign('id_mat_ci')->references('id_mat_ci')->on('materia_ciclo');

            $table->integer('id_grup_carg')->unsigned()->nullable();
            $table->foreign('id_grup_carg')->references('id_grup_carg')->on('grupo_carga');

            $table->integer('id_pdg_dcn')->unsigned();
            $table->foreign('id_pdg_dcn')->references('id_pdg_dcn')->on('pdg_dcn_docente');

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
