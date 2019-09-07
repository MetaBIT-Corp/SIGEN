<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intento', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('estudiante_id')->nullable();
            $table->foreign('estudiante_id')->references('id_est')->on('estudiante');
            $table->unsignedInteger('encuestado_id')->nullable();
            $table->foreign('encuestado_id')->references('id')->on('encuestado');
            $table->unsignedInteger('clave_id');
            $table->foreign('clave_id')->references('id')->on('clave');
            $table->unsignedInteger('encuesta_id')->nullable();
            $table->foreign('encuesta_id')->references('id')->on('encuesta');
            $table->dateTime('fecha_inicio_intento');
            $table->dateTime('fecha_final_intento')->nullable();
            $table->decimal('nota_intento',2,2)->nullable();
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
        Schema::dropIfExists('intento');
    }
}
