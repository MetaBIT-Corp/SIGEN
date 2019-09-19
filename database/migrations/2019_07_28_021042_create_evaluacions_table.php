<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluacion', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_carga');
            $table->foreign('id_carga')->references('id_carg_aca')->on('carga_academica');
            $table->integer('duracion');
            $table->integer('intentos');
            $table->string('nombre_evaluacion');
            $table->string('descripcion_evaluacion');
            $table->integer('preguntas_a_mostrar');//Cantidad de preguntas a presentar en la paginacion
            $table->boolean('revision');//parametro que indica si se permite revisión o no
            $table->boolean('habilitado')->default(1);//parametro que indica si la evaluación está habilitada o deshabilitada
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
        Schema::dropIfExists('evaluacion');
    }
}
