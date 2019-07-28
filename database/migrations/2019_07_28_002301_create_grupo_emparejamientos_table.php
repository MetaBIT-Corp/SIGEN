<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGrupoEmparejamientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grupo_emparejamiento', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_area');
            $table->foreign('id_area')->references('id')->on('area');
            $table->string('descripcion_grupo_emp');
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
        Schema::dropIfExists('grupo_emparejamiento');
    }
}
