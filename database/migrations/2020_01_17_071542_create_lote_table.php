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
            $table->double('precio',8,2);      //  precio Lote
            $table->integer('cantidad');       //  es lo que se a movido sea ingreso o egreso
            $table->integer('stock');          //  cantidad actual
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
