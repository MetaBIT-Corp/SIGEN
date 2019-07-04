<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleInscEstsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_insc_est', function (Blueprint $table) {
            $table->increments('id_insc_est');

            $table->integer('id_carg_aca')->unsigned();
            $table->foreign('id_carg_aca')->references('id_carg_aca')->on('carga_academica');

            $table->integer('id_est')->unsigned();
            $table->foreign('id_est')->references('id_est')->on('estudiante');

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
        Schema::dropIfExists('detalle_insc_est');
    }
}
