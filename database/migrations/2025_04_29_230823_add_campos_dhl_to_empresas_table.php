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
            $table->float('dimension_excedida', 12,2)->default(0);
            $table->float('peso_excedido', 12,2)->default(0);
            $table->float('pza_no_convencional', 12,2)->default(0);

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
            $table->dropColumn('dimension_excedida');
            $table->dropColumn('peso_excedido');
            $table->dropColumn('pza_no_convencional');
        });
    }
};
