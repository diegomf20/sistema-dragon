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
            $table->unsignedInteger('producto_id')
                    ->nullable();
            $table->double('precio_lote',8,2);      //  precio Lote
            $table->integer('cantidad');            //  es lo que se a movido sea ingreso o egreso
            $table->integer('stock');               //  cantidad actual
            $table->enum('tipo',['Entrada','Salida']);
            
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
        Schema::dropIfExists('kardex');
    }
}
