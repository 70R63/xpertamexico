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
        DB::statement('DROP VIEW IF EXISTS conciliacion_views');

        DB::statement("CREATE 
    ALGORITHM = UNDEFINED 
    
VIEW `conciliacion_views` AS
    SELECT 
        `carga_conciliacions`.`num_factura_ltd` AS `num_factura_ltd`,
        `carga_conciliacions`.`ltd_id` AS `ltd_id`,
        COUNT(1) AS `cantidad`,
        `carga_conciliacions`.`created_at` AS `created_at`,
        `carga_conciliacions`.`fecha_factura` AS `fecha_factura`,
        SUM(`carga_conciliacions`.`subtotal_facturado_ltd`) AS `subtotal`,
        SUM(`carga_conciliacions`.`total_facturado_ltd`) AS `total`,
        `cfg_ltds`.`nombre` AS `ltd_nombre`,
        `carga_conciliacions`.`user_id` AS `user_id`,
        `users`.`name` AS `name`
    FROM
        ((`carga_conciliacions`
        JOIN `cfg_ltds` ON ((`cfg_ltds`.`id` = `carga_conciliacions`.`ltd_id`)))
        JOIN `users` ON ((`users`.`id` = `carga_conciliacions`.`user_id`)))
    GROUP BY `carga_conciliacions`.`num_factura_ltd`  ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS reportes_repesajes');
    }
};
