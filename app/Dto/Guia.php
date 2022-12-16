<?php

namespace App\Dto;

use Illuminate\Support\Facades\Storage;
use Log;
use Carbon\Carbon;
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
		$this->insert = array('usuario' => ""
				,'empresa_id' 	=> ""
				,'ltd_id' 	=> ""
				,'cia' 		=> ""
				,'cia_d' 	=> ""
				,'piezas' 	=> ""
				, 'documento' => ""
				,'tracking_number'=>""
				,'servicio_id'	=>""
				,'peso'			=> ""
				,'dimensiones'	=> ""
				,'extendida'	=> ""
				,'seguro'		=> ""
				,'valor_envio'	=> ""
				,'precio'		=> ""
			);
	}

	/**
	 * 
	 */

	public function parser($request, $sFedex){

		$dimensiones = sprintf("%sx%sx%s",$request->largo,$request->ancho,$request->alto);
		$precio = sprintf("%.2f",$request['precio']);
		$this->insert = array('usuario' => auth()->user()->name
				,'empresa_id' 	=> auth()->user()->empresa_id
				,'ltd_id' 	=> $request->ltd_id
				,'cia' 		=> $request->sucursal_id
				,'cia_d' 	=> $request->cliente_id
				,'piezas' 	=> $request->piezas
				, 'documento' => $sFedex->documento
				,'tracking_number' 	=>$sFedex->getTrackingNumber()
				,'servicio_id'		=>$request->servicio_id
				,'peso'			=> $request->peso_facturado
				,'dimensiones'	=> $dimensiones
				,'extendida'	=> $request['extendida']
				,'seguro'		=> $request['costo_seguro']
				,'valor_envio'	=> $request['valor_envio']
				,'precio'		=> $precio
			);

	}

	static public function estafeta($sEstafeta, $request, $canal = "API"){
		Log::info(__CLASS__." ".__FUNCTION__." INICIANDO ".$canal);
		

		$cia = 1;
		$cia_d = 1;
		$extendida = "NO";
		$costoSeguro = sprintf("%.2f",$request['costo_seguro']);
		$valorEnvio = sprintf("%.2f",$request['valor_envio']);
		$precio = 0;
	
		if ($canal === "WEB") {
			$cia = $request['sucursal_id'];
			$cia_d = $request['cliente_id'];
			$servicioId = $request['servicio_id'];
			$peso = $request['peso_facturado'];
			$dimensiones = sprintf("%sx%sx%s",$request['largo'],$request['ancho'],$request['alto']);
			$extendida = $request['extendida'];
			$usuario = auth()->user()->name;
			$empresa_id = auth()->user()->empresa_id;
			$piezas = $request['piezas'];
			$precio = sprintf("%.2f",$request['precio']);

		}

		if ($canal === "API") {
			//Servicio 1= FEDEX
			$servicioId=1;
			$servicio_name = $request['labelDefinition']['serviceConfiguration']['serviceTypeId'];
			$peso = $request['labelDefinition']['itemDescription']['weight'];
			$dimensiones = sprintf("%sx%sx%s",$request['labelDefinition']['itemDescription']['length'],$request['labelDefinition']['itemDescription']['width'],$request['labelDefinition']['itemDescription']['height']);			
			
			if ($servicio_name === Config('ltd.estafeta.servicio.3')) {
				$servicioId=3;
			} elseif ( $servicio_name === Config('ltd.estafeta.servicio.2') ) {
				$servicioId=2;
			} 
			$usuario = $request['name'];
			$empresa_id = $request['empresa_id'];
			$piezas = $request['labelDefinition']['serviceConfiguration']['quantityOfLabels'];
		}

		$carbon = Carbon::parse();

		$unique = bcrypt((string)$carbon);
		$carbon->settings(['toStringFormat' => 'Y-m-d']);


		$namePdf = sprintf("%s-%s.pdf",(string)$carbon,$unique);
		Storage::disk('public')->put($namePdf,base64_decode($sEstafeta->documento));
		
		$insert = array('usuario' => $usuario
				,'empresa_id' 	=> $empresa_id
				,'ltd_id' 	=> Config('ltd.estafeta.id')
				,'cia' 		=> $cia
				,'cia_d' 	=> $cia_d
				,'piezas' 	=> $piezas
				,'documento' => $namePdf
				,'tracking_number' =>$sEstafeta->getTrackingNumber()
				,'canal'	=> $canal
				,'servicio_id'	=> $servicioId
				,'peso'			=> $peso
				,'dimensiones'	=> $dimensiones
				,'extendida'	=> $extendida
				,'seguro'		=> $costoSeguro
				,'valor_envio'	=> $valorEnvio
				,'precio'		=> $precio

 			);
		Log::info(__CLASS__." ".__FUNCTION__." FINALIZNADO ".$canal);
		return $insert;
	}
}

?>