<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnasToActivo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activo', function (Blueprint $table) {
            $table->string('codigo', 12)->change();
            $table->double('precio_compra',9,2)->nullable();
            $table->date('fecha_compra')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activo', function (Blueprint $table) {
            //
        });
    }
}
