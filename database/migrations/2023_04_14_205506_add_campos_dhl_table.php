<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->unsignedTinyInteger('descuento')->default(0);
            $table->unsignedTinyInteger('fsc')->default(0);
            $table->unsignedTinyInteger('area_extendida')->default(0);
            $table->unsignedTinyInteger('precio_mulitpieza')->default(0);
            $table->unsignedTinyInteger('premium10')->default(0);
            $table->unsignedTinyInteger('premium12')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn('descuento');
            $table->dropColumn('fsc');
            $table->dropColumn('area_extendida');
            $table->dropColumn('precio_mulitpieza');
            $table->dropColumn('premium10');
            $table->dropColumn('premium12');
        });
    }
};
