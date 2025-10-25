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
        Schema::table('guias', function (Blueprint $table) {
            $table->float('dimension_excedida_costo', 12,2)->default(0);
            $table->float('peso_excedido_costo', 12,2)->default(0);
            $table->float('pza_no_convencional_costo', 12,2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('guias', function (Blueprint $table) {
            $table->dropColumn('dimension_excedida_costo');
            $table->dropColumn('peso_excedido_costo');
            $table->dropColumn('pza_no_convencional_costo');
        });
    }
};
