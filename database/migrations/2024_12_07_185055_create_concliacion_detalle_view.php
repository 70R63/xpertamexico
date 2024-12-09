<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('DROP VIEW IF EXISTS conciliacion_detalle_views');

        DB::statement("
            CREATE 
            ALGORITHM = UNDEFINED 
            VIEW `conciliacion_detalle_views` AS
                SELECT 
        `carga_conciliacions`.`num_factura_ltd` AS `num_factura_ltd`,
        `carga_conciliacions`.`ltd_id` AS `ltd_id`,
        `carga_conciliacions`.`created_at` AS `created_at`,
        `carga_conciliacions`.`fecha_factura` AS `fecha_factura`,
        `carga_conciliacions`.`tracking_number` AS `tracking_number`,
        `carga_conciliacions`.`subtotal_facturado_ltd` AS `subtotal_facturado_ltd`,
        `carga_conciliacions`.`fecha_envio` AS `fecha_envio`,
        `guias`.`cia` AS `cia`,
        `guias`.`costo_base` AS `costo_base`,
        (`guias`.`costo_base` - `carga_conciliacions`.`subtotal_facturado_ltd`) AS `utilidad_monetaria`,
        ROUND((((`guias`.`costo_base` - `carga_conciliacions`.`subtotal_facturado_ltd`) / `guias`.`costo_base`) * 100),
                2) AS `utilidad_porcentaje`,
        ROUND(((`carga_conciliacions`.`subtotal_facturado_ltd` / `guias`.`costo_base`) * 100),
                2) AS `costo_venta_porcentaje`,
        `sucursals`.`nombre` AS `nombre`
    FROM
        ((`carga_conciliacions`
        LEFT JOIN `guias` ON (((`guias`.`tracking_number` = `carga_conciliacions`.`tracking_number`)
            AND (`guias`.`ltd_id` = `carga_conciliacions`.`ltd_id`))))
        LEFT JOIN `sucursals` ON ((`sucursals`.`id` = `guias`.`cia`)))
                ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conciliacion_detalle_views');
    }
};
