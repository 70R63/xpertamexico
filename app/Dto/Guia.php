<?php

namespace App\Dto;

use Illuminate\Support\Facades\Storage;
use Log;
/**
 * 
 */
class Guia {
	/**
     * Insert , array que conse usara para insertar como si fuera un request
     *
     * @var nombre
     */
    public $insert;
	
	function __construct()
	{
		// code...
	}

	/**
	 * 
	 */

	public function parser($request, $sFedex){
		$this->insert = array('usuario' => auth()->user()->name
				,'empresa_id' 	=> auth()->user()->empresa_id
				,'ltd_id' 	=> $request->ltd_id
				,'cia' 		=> $request->sucursal_id
				,'cia_d' 	=> $request->cliente_id
				,'piezas' 	=> $request->piezas
				, 'documento' => $sFedex->documento
				,'tracking_number' 	=>$sFedex->getTrackingNumber()
				,'servicio_id'		=>$request->servicio_id
			);
	}

	static public function estafeta($sEstafeta, $request, $canal = "API"){
		$cia = 1;
		$cia_d = 1;
		$piezas= 1;

		if ($canal === "WEB") {
			$cia = $request['sucursal_id'];
			$cia_d = $request['cliente_id'];
			$piezas = $request['piezas'];
			$servicioId = $request['servicio_id'];
		}
		if ($canal === "API") {
			
			$servicioId=1;
			$servicio_name = $request['labelDefinition']['serviceConfiguration']['serviceTypeId'];
			
			if ($servicio_name === Config('ltd.estafeta.servicio.3')) {
				$servicioId=3;
			} elseif ( $servicio_name === Config('ltd.estafeta.servicio.2') ) {
				$servicioId=2;
			} 
			
		}

		$namePdf = sprintf("%s.pdf",$sEstafeta->getTrackingNumber());
		Storage::disk('public')->put($namePdf,base64_decode($sEstafeta->documento));
		//Log::debug(print_r(Storage::disk('local'),true));
		$insert = array('usuario' => auth()->user()->name
				,'empresa_id' 	=> auth()->user()->empresa_id
				,'ltd_id' 	=> Config('ltd.estafeta.id')
				,'cia' 		=> $cia
				,'cia_d' 	=> $cia_d
				,'piezas' 	=> $piezas
				, 'documento' => $namePdf
				,'tracking_number' =>$sEstafeta->getTrackingNumber()
				,'canal'	=> $canal
				,'servicio_id'	=> $servicioId
			);

		return $insert;
	}
}

?>