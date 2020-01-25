<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKardexTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kardex', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha');
            $table->enum('tipo',['Ingreso','Salida']);
            $table->unsignedInteger('producto_id')
                    ->nullable();
            $table->double('cantidad',8,2);       //  es lo que se a movido sea ingreso o egreso
            $table->double('stock',8,2);          //  cantidad actual
            $table->double('precio',9,3);      //  precio Lote
            $table->double('total',13,3);
            $table->unsignedInteger('lote_id')->nullable();
            $table->unsignedInteger('documento_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kardex');
    }
}
