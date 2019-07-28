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
            $table->unsignedInteger('id_estudiante');
            $table->foreign('id_estudiante')->references('id_est')->on('estudiante');
            $table->unsignedInteger('id_clave');
            $table->foreign('id_clave')->references('id')->on('clave');
            $table->dateTime('fecha_inicio_intento');
            $table->dateTime('fecha_final_intento');
            $table->decimal('nota_intento',2,2);
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
