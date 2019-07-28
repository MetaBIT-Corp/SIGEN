<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaveAreaPreguntasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clave_area_pregunta', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_clave_area');
            $table->foreign('id_clave_area')->references('id')->on('clave_area');
            $table->unsignedInteger('id_pregunta');
            $table->foreign('id_pregunta')->references('id')->on('pregunta');
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
        Schema::dropIfExists('clave_area_pregunta');
    }
}
