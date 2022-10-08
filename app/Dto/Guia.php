<?php

namespace App\Dto;

/**
 * 
 */
class Guia 
{
	/**
     * Insert , array que conse usara para insertar como si fura un request
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

	public function parser($request)
	{
		$this->insert = array('usuario' => auth()->user()->name
				,'empresa_id' 	=> auth()->user()->empresa_id
				,'ltd_id' 	=> $request->ltd_id
				,'cia' 		=> $request->compania
				,'cia_d' 	=> $request->compania_d
				,'piezas' 	=> 0
				, );
	}
}

?>