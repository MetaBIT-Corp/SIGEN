<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaveAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clave_area', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_area');
            $table->foreign('id_area')->references('id')->on('area');
            $table->unsignedInteger('id_clave');
            $table->foreign('id_clave')->references('id')->on('clave');
            $table->integer('numero_preguntas');
            $table->boolean('aleatorio');
            $table->integer('peso');
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
        Schema::dropIfExists('clave_area');
    }
}
