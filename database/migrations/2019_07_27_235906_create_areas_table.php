<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('area', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_cat_mat')->nullable();
            $table->foreign('id_cat_mat')->references('id_cat_mat')->on('cat_mat_materia');
            $table->unsignedInteger('id_pdg_dcn');
            $table->foreign('id_pdg_dcn')->references('id_pdg_dcn')->on('pdg_dcn_docente');
            $table->unsignedInteger('tipo_item_id');
            $table->foreign('tipo_item_id')->references('id')->on('tipo_item');
            $table->string('titulo');
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
        Schema::dropIfExists('area');
    }
}
