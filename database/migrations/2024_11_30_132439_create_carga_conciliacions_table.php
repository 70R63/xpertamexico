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
        Schema::create('detalle_conciliacions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->boolean('estatus')->default(1);

            $table->unsignedInteger('user_id')->nullable(false)->default(1);
            $table->unsignedInteger('empresa_id')->nullable(false)->default(1)->index();

            $table->string('tracking_number');
            $table->string('num_factura_ltd');
            $table->unsignedInteger('servicio_id')->nullable(false)->default(0);
            $table->date('fecha_envio')->nullable(false)->default("1999-12-31");
            $table->float('peso_facturado_ltd', 12,2)->default(0);
            $table->float('alto_facturado_ltd', 12,2)->default(0);
            $table->float('largo_facturado_ltd', 12,2)->default(0);
            $table->float('ancho_facturado_ltd', 12,2)->default(0);
            $table->float('subtotal_facturado_ltd', 12,2)->default(0);
            $table->float('total_facturado_ltd', 12,2)->default(0);

            $table->float('adicional_ae_ltd', 12,2)->default(0);
            $table->float('adicional_seguro_ltd', 12,2)->default(0);
            $table->float('adicional_envio_irregular_ltd', 12,2)->default(0);
            $table->float('adicional_correcion_ltd', 12,2)->default(0);
            $table->float('adicional_exceso_dimension_ltd', 12,2)->default(0);


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carga_conciliacions');
    }
};
