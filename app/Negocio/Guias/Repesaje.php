<?php

namespace App\Negocio\Guias;

//GENERALES
use Log;

use Illuminate\Validation\ValidationException;

//MODELS
use App\Models\API\Guia as GuiaAPI;
use App\Models\API\Tarifa as Tarifa;
use App\Models\API\EmpresaLtd;


class Repesaje {

	private $precioRastreo = 0;
	private $esRepesaje = 0;


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

	public function calcularPrecio( $guia_id, array $dimensionesRastreo,$data){
		Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);

		
		$pesoFacturadoRastreo = ceil(($dimensionesRastreo['peso'] > $dimensionesRastreo['peso_dimensional_rastreo']) ? $dimensionesRastreo['peso'] : $dimensionesRastreo['peso_dimensional_rastreo']);

		$pesoFacturado = $data['peso'];
		$tarifaId = $data['tarifa_id'];

		Log::debug(__CLASS__." ".__FUNCTION__." ".__LINE__." $pesoFacturadoRastreo > $pesoFacturado");

		if ($pesoFacturadoRastreo > $data['peso'] ) {
			Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." Se tiene que recalcular el precio");
			
			Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." Valida EmpresaLtd ");
			$empresaLtd = EmpresaLtd::select("tarifa_clasificacion")
					->where('ltd_id',$data['ltd_id'])
					->where('empresa_id',$data['empresa_id'])
					->get()->toArray();

			Log::debug(__CLASS__." ".__FUNCTION__." ".__LINE__." ".print_r($empresaLtd,true));
				
			if (count($empresaLtd) > 0) {
				Log::debug(__CLASS__." ".__FUNCTION__." ".__LINE__." Asignacion de LTD");


				$tarifa = Tarifa::select("id","kg_ini", "kg_fin", "kg_extra", "costo")
					->where("id",$data['tarifa_id'])
					->firstOrFail();

				Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." Tarifa_id= $tarifaId");
				Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." $pesoFacturadoRastreo > $tarifa->kg_fin");


				switch ($empresaLtd[0]['tarifa_clasificacion']) {
					case 1:
						Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." TARIFA FLAT");

						if ($pesoFacturadoRastreo > $tarifa->kg_fin) {
							Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." Peso Maximo exedido");

							$pesoDiferencia = $pesoFacturadoRastreo-$data['peso'];

							$costoExtraDiferencia =$pesoDiferencia*  $tarifa->kg_extra;
							Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." costoExtraDiferencia=$costoExtraDiferencia");
							$this->precioRastreo = $data['precio'] + ($costoExtraDiferencia*1.16);

							$this->esRepesaje = 1;

						} else {
							Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." Peso en rango Maximo");
						}
						break;

					case 2:
						Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." TARIFA RANGO");

						if ($pesoFacturadoRastreo > $tarifa->kg_fin) {
							Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." Peso Maximo exedido");


							$tarifaNueva = Tarifa::select("id","kg_ini", "kg_fin", "kg_extra", "costo")
								->where("kg_ini", ">=",$pesoFacturadoRastreo)
								->where("kg_fin", "=<",$pesoFacturadoRastreo)
								->get()->toArray();

							Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
							if ( count($tarifaNueva) >0) {
								$this->precioRastreo = $tarifaNueva[0]['costo'] * 1.16;
							} else {
								
							}
							$this->esRepesaje = 1;

						} else {
							Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." Peso en rango Maximo");
						}



						break;
					default:
						Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." TARIFA DEFAULT");
						break;
				}

			} else {
				Log::debug(__CLASS__." ".__FUNCTION__." ".__LINE__." Sin Asignacion LTD");
			}

		} else {
			Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." No necesita realizar recalculo");
			//throw ValidationException::withMessages(array("No necesita realizar recalculo"));
		}

		Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
	}


	public function getPrecioRastreo(){

		return $this->precioRastreo;
	}

	public function getEsRepesaje(){

		return $this->esRepesaje;
	}
}