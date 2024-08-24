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
            $table->unsignedInteger('tarifa_id')->nullable(false)->default(0);
            $table->float('peso_dimensional_rastreo', 12,2)->default(0);
            $table->float('precio_rastreo', 12,2)->default(0);

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
            $table->dropColumn('tarifa_id');
            $table->dropColumn('peso_dimensional_rastreo');
            $table->dropColumn('precio_rastreo');
        });
    }
};
