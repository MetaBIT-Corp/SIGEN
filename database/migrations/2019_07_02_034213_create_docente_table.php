<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocenteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pdg_dcn_docente', function (Blueprint $table) {
            $table->increments('id_pdg_dcn');

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('carnet_dcn');
            $table->string('anio_titulo');
            $table->integer('activo');
            $table->integer('tipo_jornada');
            $table->text('descripcion_docente');
            $table->integer('id_cargo_actual');
            $table->integer('id_segundo_cargo');
            $table->string('nombre_docente');

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
        Schema::dropIfExists('pdg_dcn_docente');
    }
}
