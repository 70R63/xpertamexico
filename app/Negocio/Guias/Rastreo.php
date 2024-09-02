<?php

namespace App\Negocio\Guias;

use Log;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

//MODELS


//DTOS


// singlenton


//Negocio


class Rastreo {

	private $update = array();

	
	/**
     * Se parsean datos para el update de los datas de una guia
     * posterior al rastreo
     * 
     * @author Javier Hernandez
     * @copyright 2022-2024 XpertaMexico
     * @package App\Negocio\Guias
     * @api
     * 
     * @version 1.0.0
     * 
     * @since 1.0.0 Primera version de la funcion parseoUpdate
     * 
     * @throws \LogicException
     *
     * @param date $ultimaFecha Fecha de rastreo
     * @param int $rastreoEstatus Estatus del rastreo 
     * @param string $quienRecibio Nombre de quiern recibe el paquete
     * @param date $pickupFecha Fecha de recoleccion
     * @param doble $precioRastreo Valor recalculado despues de rastreo
     * @param array $paquete Conjunto de datos de dimensiones despues de rastreo
     * 
     * @var int 
     * @var App\Negocio\Fedex_tarifas $fedexTarifa
     * @var string $cp 
     * @var string $cp_d
     * @var array $body valores unicos par envio al LTD
     * @var string $canal valor que indentifica de donde se realiza la peticion
     * 
     * 
     * @return array conjunto de valor parseado para actualizar la guia 
     */

    public function parseoUpdate($ultimaFecha, $rastreoEstatus,$quienRecibio,$pickupFecha , $precioRastreo, array $paquete){
       

        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." armando update");
        $this->update = array('ultima_fecha' => $ultimaFecha
                ,'rastreo_estatus' => $rastreoEstatus
                ,'rastreo_peso' => $paquete['peso_rastreo'] 
                ,'largo' => $paquete['largo'] 
                ,'ancho' => $paquete['ancho'] 
                ,'alto' => $paquete['alto']
                ,'quien_recibio' =>  $quienRecibio
                ,'pickup_fecha' =>  $pickupFecha
                ,'peso_dimensional_rastreo' => $paquete['peso_dimensional_rastreo'] 
                ,'precio_rastreo' => $precioRastreo
                ,'peso_facturado_rastreo' => $paquete['peso_facturado_rastreo']

            );

        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
    }

    public function getUpdate(){
    	return $this->update;

    }

}