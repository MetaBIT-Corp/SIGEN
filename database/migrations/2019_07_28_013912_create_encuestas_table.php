<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEncuestasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('encuesta', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_docente');
            $table->foreign('id_docente')->references('id_pdg_dcn')->on('pdg_dcn_docente');
            $table->string('titulo_encuesta');
            $table->text('descripcion_encuesta')->nullable();
            $table->dateTime('fecha_inicio_encuesta');
            $table->dateTime('fecha_final_encuesta');
            $table->boolean('visible');
            $table->string('ruta')->nullable();
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
        Schema::dropIfExists('encuesta');
    }
}
