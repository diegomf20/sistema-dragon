<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lote', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('insumo_id');
            $table->double('precio',9,3);      //  precio Lote
            $table->double('cantidad',8,2);       //  es lo que se a movido sea ingreso o egreso
            $table->double('stock',8,2);          //  cantidad actual
            $table->date('fecha_ingreso');
            $table->unsignedInteger('movimiento_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lote');
    }
}
