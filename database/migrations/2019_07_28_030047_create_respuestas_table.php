<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRespuestasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('respuesta', function (Blueprint $table) {
            $table->increments('id');
            $table->string('texto_respuesta')->nullable();

            //Relaciones a otros modelos
            $table->unsignedInteger('id_pregunta');
            $table->foreign('id_pregunta')->references('id')->on('pregunta');
            $table->unsignedInteger('id_opcion')->nullable();
            $table->foreign('id_opcion')->references('id')->on('opcion');
            $table->unsignedInteger('id_intento');
            $table->foreign('id_intento')->references('id')->on('intento');

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
        Schema::dropIfExists('respuesta');
    }
}
