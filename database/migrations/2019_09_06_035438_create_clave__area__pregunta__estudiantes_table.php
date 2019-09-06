<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaveAreaPreguntaEstudiantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clave_area_pregunta_estudiantes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('estudiante_id');
            $table->foreign('estudiante_id')->references('id_est')->on('estudiante');
            $table->unsignedInteger('clave_area_id');
            $table->foreign('clave_area_id')->references('id')->on('clave_area');
            $table->unsignedInteger('pregunta_id');
            $table->foreign('pregunta_id')->references('id')->on('pregunta');
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
        Schema::dropIfExists('clave__area__pregunta__estudiantes');
    }
}
