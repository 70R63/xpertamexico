<?php

namespace App\Negocio\Guias;

//GENERALES
use Log;

//MODELS
use App\Models\API\Guia as GuiaAPI;
use App\Models\API\Tarifa as Tarifa;


class Repesaje {

	private $precioRastreo = 0;


	/**
     * Se obtienen los datos para armar el insert de fedex
     * 
     * @author Javier Hernandez
     * @copyright 2022-2023 XpertaMexico
     * @package App\Negocio\Guias
     * @api
     * 
     * @version 1.0.0
     * 
     * @since 1.0.0 Primera version de la funcion fedexApi
     * 
     * @throws \LogicException
     *
     * @param array $parametros eseseses
     * 
     * @var int 
     * @var App\Negocio\Fedex_tarifas $fedexTarifa
     * @var string $cp 
     * @var string $cp_d
     * @var array $body valores unicos par envio al LTD
     * @var string $canal valor que indentifica de donde se realiza la peticion
     * 
     * 
     * @return json Objeto con la respuesta de exito o fallo 
     */

	public function calcularPrecio( $guia_id, array $dimensionesRastreo,$precio){
		Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);

		$guia = GuiaAPI::select("peso", "tarifa_id")
				->where("id", $guia_id)
				->firstOrFail();

		$pesoFacturadoRastreo = ceil(($dimensionesRastreo['peso'] > $dimensionesRastreo['peso_dimensional_rastreo']) ? $dimensionesRastreo['peso'] : $dimensionesRastreo['peso_dimensional_rastreo']);

		

		Log::debug(__CLASS__." ".__FUNCTION__." ".__LINE__." $pesoFacturadoRastreo > $guia->peso");

		if ($pesoFacturadoRastreo > $guia->peso ) {
			Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." Se tiene que recalcular el precio");
			$tarifa = Tarifa::select("id","kg_ini", "kg_fin", "kg_extra", "costo")
				->where("id",$guia->tarifa_id)
				->firstOrFail();
			Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." Tarifa_id= $guia->tarifa_id");

			Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." $pesoFacturadoRastreo > $tarifa->kg_fin");
			if ($pesoFacturadoRastreo > $tarifa->kg_fin) {
				Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." Peso Maximo exedido");

				$pesoDiferencia = $pesoFacturadoRastreo-$tarifa->kg_fin;

				$costoExtraDiferencia =$pesoDiferencia*  $tarifa->kg_extra;
				Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." costoExtraDiferencia=$costoExtraDiferencia");
				$this->precioRastreo = $precio + ($costoExtraDiferencia*1.16);

			} else {
				Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." Peso en rango Maximo");
			}
			

		} else {
			Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." No necesita realizar recalculo");
		}
		
		

		Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
	}


	public function getPrecioRastreo(){

		return $this->precioRastreo;
	}
}