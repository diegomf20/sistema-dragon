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
            $table->integer('cantidad');       //  es lo que se a movido sea ingreso o egreso
            $table->integer('stock');          //  cantidad actual
            $table->double('precio',8,2);      //  precio Lote
            $table->double('total',12,2);
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
