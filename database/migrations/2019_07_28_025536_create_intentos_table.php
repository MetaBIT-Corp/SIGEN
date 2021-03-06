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
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedInteger('clave_id');
            $table->foreign('clave_id')->references('id')->on('clave');
            $table->integer('numero_intento');
            $table->dateTime('fecha_inicio_intento');
            $table->dateTime('fecha_final_intento')->nullable();
            $table->decimal('nota_intento',4,2)->nullable();
            $table->boolean('revision_estudiante')->default(0);
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
