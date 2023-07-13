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
        Schema::table('ltd_tipo_servicios', function (Blueprint $table) {
            $table->string('sales_organization', 5)->nullable(false)->default('112');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ltd_tipo_servicios', function (Blueprint $table) {
             $table->dropColumn('sales_organization');
        });
    }
};
