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
         DB::statement('DROP VIEW IF EXISTS reportes_ventas');

        DB::statement("
            CREATE 
            ALGORITHM = UNDEFINED 
            VIEW `reportes_ventas` AS
            SELECT 
                `g`.`id` AS `id`,
                `g`.`empresa_id` AS `empresa_id`,
                `g`.`usuario` AS `usuario`,
                `g`.`ltd_id` AS `ltd_id`,
                `g`.`tracking_number` AS `tracking_number`,
                `g`.`servicio_id` AS `servicio_id`,
                `g`.`created_at` AS `created_at`,
                `g`.`cia` AS `cia`,
                `g`.`cia_d` AS `cia_d`,
                `g`.`pickup_fecha` AS `pickup_fecha`,
                `g`.`zona` AS `zona`,
                `g`.`rastreo_estatus` AS `rastreo_estatus`,
                `g`.`seguro` AS `seguro`,
                `g`.`precio` AS `precio`,
                `g`.`peso` AS `peso_facturado`,
                `g`.`costo_base` AS `costo_base`,
                `g`.`costo_kg_extra` AS `costo_kg_extra`,
                `g`.`peso_dimensional` AS `peso_dimensional`,
                `g`.`peso_bascula` AS `peso_bascula`,
                `g`.`sobre_peso_kg` AS `sobre_peso_kg`,
                `g`.`costo_extendida` AS `costo_extendida`,
                `g`.`numero_solicitud` AS `numero_solicitud`,
                `g`.`servicio_premium` AS `servicio_premium`,
                `g`.`multipieza` AS `multipieza`,
                `g`.`dimension_excedida_costo`,
                `g`.`estatus` AS `estatus`,
                `g`.`peso_excedido_costo` ,
                `g`.`pza_no_convencional_costo` ,

                `s`.`cp` AS `cp_origen`,
                `s`.`ciudad` AS `ciudad_origen`,
                `s`.`entidad_federativa` AS `entidad_federativa_origen`,
                `s`.`contacto` AS `contacto_origen`,
                `c`.`nombre` AS `nombre_destino`,
                `c`.`cp` AS `cp_destino`,
                `c`.`ciudad` AS `ciudad_destino`,
                `c`.`entidad_federativa` AS `entidad_federativa_destino`,
                `c`.`contacto` AS `contacto_destino`,
                `re`.`nombre` AS `rastreo_nombre`,
                `gp`.`peso` AS `peso_paquete`,
                SUM(`gp`.`alto`) AS `alto`,
                SUM(`gp`.`ancho`) AS `ancho`,
                SUM(`gp`.`largo`) AS `largo`,
                `s2`.`nombre` AS `servicio_nombre`,
                `cl`.`nombre` AS `ltd_nombre`,
                `e`.`nombre` AS `clilente_xperta`
            FROM
                (((((((`guias` `g`
                JOIN `sucursals` `s` ON ((`s`.`id` = `g`.`cia`)))
                JOIN `clientes` `c` ON ((`c`.`id` = `g`.`cia_d`)))
                JOIN `rastreo_estatus` `re` ON ((`re`.`id` = `g`.`rastreo_estatus`)))
                JOIN `guias_paquetes` `gp` ON ((`gp`.`guia_id` = `g`.`id`)))
                JOIN `servicios` `s2` ON ((`s2`.`id` = `g`.`servicio_id`)))
                JOIN `cfg_ltds` `cl` ON ((`cl`.`id` = `g`.`ltd_id`)))
                JOIN `empresas` `e` ON ((`e`.`id` = `s`.`empresa_id`)))
            WHERE
                (`g`.`estatus` = 1)
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
        Schema::dropIfExists('reportes_ventas');
    }
};
