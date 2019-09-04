<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTurnosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('turno', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('evaluacion_id');
            $table->foreign('evaluacion_id')->references('id')->on('evaluacion');
            $table->dateTime('fecha_inicio_turno');
            $table->dateTime('fecha_final_turno');
            $table->boolean('visibilidad');
            $table->string('contraseña');
            $table->softDeletes();
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
        Schema::dropIfExists('turno');
    }
}
