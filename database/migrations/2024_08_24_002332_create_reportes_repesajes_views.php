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
        DB::statement('DROP VIEW IF EXISTS reportes_repesajes');

        DB::statement("
          CREATE 
    ALGORITHM = UNDEFINED 
    SQL SECURITY DEFINER
VIEW `reportes_repesajes` AS
    SELECT 
        `g`.`id` AS `id`,
        `g`.`ltd_id` AS `ltd_id`,
        `g`.`pickup_fecha` AS `pickup_fecha`,
        `g`.`peso` AS `peso_facturado`,
        `g`.`peso_dimensional` AS `peso_dimensional`,
        `g`.`peso_bascula` AS `peso_bascula`,
        SUM(`g`.`alto`) AS `alto_rastreo`,
        SUM(`g`.`ancho`) AS `ancho_rastreo`,
        SUM(`g`.`largo`) AS `largo_rastreo`,
        `g`.`sobre_peso_kg` AS `sobre_peso_kg`,
        `g`.`costo_base` AS `costo_base`,
        `g`.`costo_kg_extra` AS `costo_kg_extra`,
        `g`.`costo_extendida` AS `costo_extendida`,
        `g`.`seguro` AS `seguro`,
        `g`.`empresa_id` AS `empresa_id`,
        `g`.`created_at` AS `created_at`,
        `g`.`tracking_number` AS `tracking_number`,
        `g`.`precio` AS `precio`,
        `g`.`precio_rastreo` AS `precio_rastreo`,
        `e2`.`nombre` AS `empresa_nombre`,
        `e`.`nombre` AS `cliente_xperta`,
        `cl`.`nombre` AS `ltd_nombre`,
        `s2`.`nombre` AS `servicio_nombre`,
        SUM(`gp`.`alto`) AS `alto`,
        SUM(`gp`.`ancho`) AS `ancho`,
        SUM(`gp`.`largo`) AS `largo`,
        `re`.`nombre` AS `rastreo_nombre`
    FROM
        (((((((`guias` `g`
        JOIN `sucursals` `s` ON ((`s`.`id` = `g`.`cia`)))
        JOIN `empresas` `e` ON ((`e`.`id` = `s`.`empresa_id`)))
        JOIN `empresas` `e2` ON ((`e2`.`id` = `g`.`empresa_id`)))
        JOIN `cfg_ltds` `cl` ON ((`cl`.`id` = `g`.`ltd_id`)))
        JOIN `servicios` `s2` ON ((`s2`.`id` = `g`.`servicio_id`)))
        JOIN `guias_paquetes` `gp` ON ((`gp`.`guia_id` = `g`.`id`)))
        JOIN `rastreo_estatus` `re` ON ((`re`.`id` = `g`.`rastreo_estatus`)))
    GROUP BY `g`.`tracking_number`
    ORDER BY `g`.`id` DESC
        ");
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
