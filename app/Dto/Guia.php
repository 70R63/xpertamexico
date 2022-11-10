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
				,'tracking_number' =>$sFedex->getTrackingNumber()
			);
	}

	static public function estafeta($sEstafeta){

		$namePdf = sprintf("%s.pdf",$sEstafeta->getTrackingNumber());
		Storage::disk('public')->put($namePdf,base64_decode($sEstafeta->documento));
		//Log::debug(print_r(Storage::disk('local'),true));
		$insert = array('usuario' => auth()->user()->name
				,'empresa_id' 	=> auth()->user()->empresa_id
				,'ltd_id' 	=> Config('ltd.estafeta.id')
				,'cia' 		=> 2
				,'cia_d' 	=> 1
				,'piezas' 	=> 1
				, 'documento' => $namePdf
				,'tracking_number' =>$sEstafeta->getTrackingNumber()
			);

		return $insert;
	}
}

?>