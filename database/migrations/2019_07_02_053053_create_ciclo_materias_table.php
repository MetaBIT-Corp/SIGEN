<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCicloMateriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciclo_materia', function (Blueprint $table) {
            $table->increments('id_mat_ci');

            $table->integer('id_cat_mat')->unsigned();
            $table->foreign('id_cat_mat')->references('id_cat_mat')->on('cat_mat_materia');

            $table->integer('id_ciclo')->unsigned();
            $table->foreign('id_ciclo')->references('id_ciclo')->on('ciclo');

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
        Schema::dropIfExists('ciclo_materia');
    }
}
