<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salida', function (Blueprint $table) {
            $table->id('idSalida');
            $table->integer('idvehiculo');
            $table->datetime('fecha_entrada');
            $table->datetime('fecha_salida');
            $table->integer('duracion');
            $table->decimal('cobro');
            $table->integer('idusuario');
            $table->integer('idEntrada');
            $table->string('estado');
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
        Schema::dropIfExists('salida');
    }
}
