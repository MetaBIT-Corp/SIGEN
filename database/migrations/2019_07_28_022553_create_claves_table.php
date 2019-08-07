<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clave', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('turno_id')->nullable();
            $table->foreign('turno_id')->references('id')->on('turno');
            $table->unsignedInteger('encuesta_id')->nullable();
            $table->foreign('encuesta_id')->references('id')->on('encuesta');
            $table->integer('numero_clave');
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
        Schema::dropIfExists('clave');
    }
}
