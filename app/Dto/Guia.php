<?php

namespace App\Dto;

use Illuminate\Support\Facades\Storage;
use Log;
use Carbon\Carbon;

use App\Models\API\Empresa;
use App\Models\API\Sucursal;
use App\Models\API\Cliente;

/**
 * 
 */
class Guia {
	/**
     * Insert , array que conse usara para insertar como si fuera un request
     *
     * @var nombre
     */
    private $insert;
	
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

	public function parseoFedex($request, $sFedex, $canal = "API"){
		Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." INICIANDO----- ");
		$dimensiones = sprintf("%sx%sx%s",$request->largo,$request->ancho,$request->alto);
		$precio = sprintf("%.2f",$request['precio']);

		$cia = $request['sucursal_id'];
		$cia_d = $request['cliente_id'];

		switch ($request['esManual']) {
			case "SI":
			    Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." esManual = si ");
			    $canal = "MNL" ;
			    break;
			case "NO":
				Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." esManual = no ");
			  	
			    break;
			case "SEMI":
				Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." esManual = semi ");
			    $canal = "SML" ;
			    
			    break;
			case "RETORNO":
			    Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." SECCION RETORNO ");
				$canal = "RET" ;
				$cia_d = $request['sucursal_id'];
				$cia = $request['cliente_id'];
			    break;
			    
		 	default:
		    	Log::info("No se cargo ningun caso");
		}


		$this->insert = array('usuario' => auth()->user()->name
				,'empresa_id' 	=> auth()->user()->empresa_id
				,'ltd_id' 	=> $request->ltd_id
				,'cia' 		=> $cia
				,'cia_d' 	=> $cia_d
				,'piezas' 	=> $request->piezas
				, 'documento' => ""
				,'tracking_number' 	=>""
				,'servicio_id'		=>$request->servicio_id
				,'peso'			=> $request->peso_facturado
				,'dimensiones'	=> $dimensiones
				,'extendida'	=> $request['extendida']
				,'seguro'		=> $request['costo_seguro']
				,'valor_envio'	=> $request['valor_envio']
				,'precio'		=> $precio
				,'contenido'	=> empty($request['contenido']) ? "" :$request['contenido']
				,'canal'		=> $canal
				,'created_at'	=> Carbon::now()->toDateTimeString()

			);
		Log::debug(print_r($this->insert,true));
		Log::info(__CLASS__." ".__FUNCTION__." FINALIZNADO----- ");
	}

	static public function estafeta($sEstafeta, $request, $canal = "API"){
		Log::info(__CLASS__." ".__FUNCTION__." INICIANDO ".$canal);
		
		$extendida = "NO";
		$costoSeguro = sprintf("%.2f",$request['costo_seguro']);
		$valorEnvio = sprintf("%.2f",$request['valor_envio']);
		$precio = 0;
	
		if ($canal === "WEB") {
			Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." CANAL WEB ");
			$cia = $request['sucursal_id'];
			$cia_d = $request['cliente_id'];

			$empresa_id = auth()->user()->empresa_id;
			switch ($request['esManual']) {
				case "SI":
				    Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." esManual = si ");
				    $canal = "MNL" ;
				    $empresa_id = $request['empresa_id'];
				    break;
				case "NO":
					Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." esManual = no ");
				  	
				    break;
				case "SEMI":
					Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." esManual = semi ");
				    $canal = "SML" ;
				    $empresa_id = $request['empresa_id'];
				    break;
				case "RETORNO":
				    Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." SECCION RETORNO ");
					$canal = "RET" ;
					$cia_d = $request['sucursal_id'];
					$cia = $request['cliente_id'];
				    break;
				    
			 	default:
			    	Log::info("No se cargo ningun caso");
			}
			
			$usuario = auth()->user()->name;
			$servicioId = $request['servicio_id'];
			$peso = $request['peso_facturado'];

			$dimensiones ="";
			
			Log::debug( print_r($dimensiones,true) );

			$extendida = $request['extendida'];
			$piezas = $request['piezas'];
			$precio = sprintf("%.2f",$request['precio']);
			$contenido = empty($request['contenido']) ? "" :$request['contenido'];
			
		}


		if ($canal === "API") {
			Log::debug(print_r($request['empresa_id'],true));
			$empresa = Empresa::where("id",$request['empresa_id'])->pluck('nombre');
			Log::debug(print_r($empresa[0],true)); 
			//valida si existe el destinatario
			$cia = self::idRemitenteEstafeta($request->all(), $request['empresa_id']);
			$cia_d = self::idDestinatarioEstafeta($request->all(), $request['empresa_id']);
			

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
			$contenido = $request['labelDefinition']['wayBillDocument']['content'];
		}

		
		$insert = array('usuario' => $usuario
				,'empresa_id' 	=> $empresa_id
				,'ltd_id' 	=> Config('ltd.estafeta.id')
				,'cia' 		=> $cia
				,'cia_d' 	=> $cia_d
				,'piezas' 	=> $piezas
				,'documento' => ""
				,'tracking_number' =>""
				,'canal'	=> $canal
				,'servicio_id'	=> $servicioId
				,'peso'			=> $peso
				,'dimensiones'	=> $dimensiones
				,'extendida'	=> $extendida
				,'seguro'		=> $costoSeguro
				,'valor_envio'	=> $valorEnvio
				,'precio'		=> $precio
				,'contenido'	=> $contenido
				,'created_at'	=> Carbon::now()->toDateTimeString()

 			);

		Log::info(print_r($insert,true));
		Log::info(__CLASS__." ".__FUNCTION__." FINALIZNADO ".$canal);
		return $insert;
	}


	/**
     * redpack , Funcion para reasignar valores 
     *
     * @var $request
     * @var 
     * 
     */

	public function parseoRedpack($request, $singleton, $canal = "API", $namePdf= "sinnombre.pdf"){
		Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." INICIANDO ".$canal);

		switch ($canal) {
			case "WEB":
			    Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);

			    $usuario = auth()->user()->name;
			    $empresa_id = auth()->user()->empresa_id;

				$extendida = "NO";
				$costoSeguro = sprintf("%.2f",$request['costo_seguro']);
				$valorEnvio = sprintf("%.2f",$request['valor_envio']);
				$servicioId = $request['servicio_id'];
				$peso = $request['peso_facturado'];
				$dimensiones = sprintf("%sx%sx%s",$request['largo'],$request['ancho'],$request['alto']);
				$extendida = $request['extendida'];
				$piezas = $request['piezas'];
				$precio = sprintf("%.2f",$request['precio']);
				$contenido = empty($request['contenido']) ? "" :$request['contenido'];
				$cia = $request['sucursal_id'];
				$cia_d = $request['cliente_id'];
				$ltdId = $request['ltd_id'];

				
				switch ($request['esManual']) {
					case "SI":
					    Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." esManual = si ");
					    $canal = "MNL" ;
					    $empresa_id = $request['empresa_id'];
					    break;
					case "NO":
						Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." esManual = no ");
					  	
					    break;
					case "SEMI":
						Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." esManual = semi ");
					    $canal = "SML" ;
					    $empresa_id = $request['empresa_id'];
					    break;
					case "RETORNO":
					    Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." SECCION RETORNO ");
						$canal = "RET" ;
						$cia_d = $request['sucursal_id'];
						$cia = $request['cliente_id'];
					    break;
					    
				 	default:
				    	Log::info("No se cargo ningun caso");
				}


			    break;
			case "API":
				Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." esManual = no ");
			  	
			    break;
		 	default:
		    	Log::info("No se cargo ningun caso");
		}
		//FIN switch ($canal) {

		$this->insert = array('usuario' => $usuario
				,'empresa_id' 	=> $empresa_id
				,'ltd_id' 	=> $ltdId
				,'cia' 		=> $cia
				,'cia_d' 	=> $cia_d
				,'piezas' 	=> $piezas
				,'documento' => $namePdf
				,'tracking_number' =>$singleton->getTrackingNumber()
				,'canal'	=> $canal
				,'servicio_id'	=> $servicioId
				,'peso'			=> $peso
				,'dimensiones'	=> $dimensiones
				,'extendida'	=> $extendida
				,'seguro'		=> $costoSeguro
				,'valor_envio'	=> $valorEnvio
				,'precio'		=> $precio
				,'contenido'	=> $contenido
				,'created_at'	=> Carbon::now()->toDateTimeString()

 			);

		Log::info(print_r($this->insert,true));
		Log::info(__CLASS__." ".__FUNCTION__." FINALIZANDO ".$canal);

	}

	/**
     * Insert , array que conse usara para insertar como si fuera un request
     *
     * @var nombre
     */

	static private function insertDireccionRemitenteEstafeta(array $request, int $empresa_id){
		Log::info(__CLASS__." ".__FUNCTION__." INICIANDO ----");

		$remitente = $request['labelDefinition']['location']['origin'];
		$direccion2 = isset($remitente['address']['addressReference']) ? $remitente['address']['addressReference'] : "";
		$noInt= isset($remitente['address']['indoorInformation']) ? $remitente['address']['indoorInformation'] : "" ;

		$insertValue = array('contacto' => $remitente['contact']['contactName'], 
					'nombre' => $remitente['contact']['corporateName'],
					'direccion' => $remitente['address']['roadName'],
					'direccion2' => $direccion2,
					'cp' => $remitente['address']['zipCode'],
					'colonia' => $remitente['address']['settlementName'],
					'celular' =>  $remitente['contact']['cellPhone'],
					'no_ext' => $remitente['address']['externalNum'],
					'no_int' => $noInt,
					'empresa_id' => $empresa_id
					,'ciudad'	=> $remitente['address']['ciudad']
					,'entidad_federativa'=> $remitente['address']['entidad']
				);
		$tmp[0] = Sucursal::create($insertValue)->id;
		Log::debug(print_r($tmp,true));
		return $tmp;
		Log::info(__CLASS__." ".__FUNCTION__." FINALIZNADO ----");
	}

	/**
     * Valida si hay registro del remitente, valida con empresa_id y el nombre
     *
     * @var array $request
     * @var int empresa_id
     * 
     * @return int id, valor de la sucursal (remnitente)
     */

	static private function idRemitenteEstafeta(array $request, int $empresa_id){
		Log::info(__CLASS__." ".__FUNCTION__." INICIANDO ----");

		//valida si existe el remitente
		$nombreRemitente = $request['labelDefinition']['location']['origin']['contact']['corporateName'];
		Log::debug(print_r($nombreRemitente,true)); 

		$remitente = Sucursal::where('nombre', 'like', $nombreRemitente)
							->where('empresa_id',$empresa_id)
							->pluck('id')
							->toArray();

		Log::debug(print_r($remitente,true));
		
		if (empty($remitente)) {
			$remitente = self::insertDireccionRemitenteEstafeta($request, $empresa_id);
		}

		foreach ($remitente as $key => $value) {
			$cia = $value;
		}
		
		return $cia;
		Log::info(__CLASS__." ".__FUNCTION__." FINALIZNADO ----");
	}


	/**
     * Valida si hay registro del remitente, valida con empresa_id y el nombre
     *
     * @var array $request
     * @var int empresa_id
     * 
     * @return int id, valor de la sucursal (remnitente)
     */

	static private function idDestinatarioEstafeta(array $request, int $empresa_id){
		Log::info(__CLASS__." ".__FUNCTION__." INICIANDO ----");

		//valida si existe el remitente
		$nombre = $request['labelDefinition']['location']['destination']['homeAddress']['contact']['corporateName'];
		Log::debug(print_r($nombre,true)); 

		$remitente = Cliente::where('nombre', 'like', $nombre)
							->where('empresa_id',$empresa_id)
							->pluck('id')
							->toArray();

		Log::debug(print_r($remitente,true));
		
		if (empty($remitente)) {
			$remitente = self::insertDireccionDestinatarioEstafeta($request, $empresa_id);
		}

		foreach ($remitente as $key => $value) {
			$cia = $value;
		}
		
		return $cia;
		Log::info(__CLASS__." ".__FUNCTION__." FINALIZNADO ----");
	}

	/**
     * Insert , array que conse usara para insertar como si fuera un request
     *
     * @var nombre
     */

	static private function insertDireccionDestinatarioEstafeta(array $request, int $empresa_id){
		Log::info(__CLASS__." ".__FUNCTION__." INICIANDO ----");

		$destinatario = $request['labelDefinition']['location']['destination']['homeAddress'];
		$direccion2 = isset($destinatario['address']['addressReference']) ? $destinatario['address']['addressReference'] : "";
		$noInt= isset($destinatario['address']['indoorInformation']) ? $destinatario['address']['indoorInformation'] : "" ;

		$insertValue = array('contacto' => $destinatario['contact']['contactName'], 
					'nombre' => $destinatario['contact']['corporateName'],
					'direccion' => $destinatario['address']['roadName'],
					'direccion2' => $direccion2,
					'cp' => $destinatario['address']['zipCode'],
					'colonia' => $destinatario['address']['settlementName'],
					'celular' =>  $destinatario['contact']['cellPhone'],
					'no_ext' => $destinatario['address']['externalNum'],
					'no_int' => $noInt,
					'empresa_id' => $empresa_id
					,'ciudad'	=> $destinatario['address']['ciudad']
					,'entidad_federativa'=> $destinatario['address']['entidad']
				);
		$tmp[0] = Cliente::create($insertValue)->id;
		Log::debug(print_r($tmp,true));
		return $tmp;
		Log::info(__CLASS__." ".__FUNCTION__." FINALIZNADO ----");
	}

	

	/**
	 * Seccion para funcionalidad general de las guias
	 * 
	 * 
	 */
	
	/**
     * validaPiezasPaquete , Funciona para realizar inserts con valores de los datos del paquete para la WEB
     *
     * @var request
     * @var key
     * @var $boolPrecio
     * 
     * @return array 
     */

	static public function validaPiezasPaquete($request, $key, $boolPrecio, $guiaId, $plataforma="WEB")
	{
		switch ($plataforma) {
			case 'API':
				$guiaPaqueteInsert = self::paqueteAPI($request, $guiaId);
				break;
			
			default:
				$guiaPaqueteInsert = self::paqueteWeb($request, $key, $boolPrecio, $guiaId);
				break;
		}
		
        return $guiaPaqueteInsert;

	}

	/**
     * PaqueteAPI , Funciona para realizar asiganar valores al insert via API
     *
     * @var request
     * @var key
     * @var $boolPrecio
     * 
     * @return array 
     */

	static private function paqueteAPI($request, $guiaId)
	{
		        
		$paquete = $request["labelDefinition"]["itemDescription"];
		$precioUnitario = 0;

	    $guiaPaqueteInsert = array(
            'peso' => $paquete["weight"]
            ,'alto' => $paquete["height"]
            ,'ancho' => $paquete["width"]
            ,'largo' => $paquete["length"]
            ,'precio_unitario' => $precioUnitario
            ,'guia_id' => $guiaId
        );    
            
        
        return $guiaPaqueteInsert;
		

	}

	/**
     * PaqueteWeb , Funciona para realizar inserts con valores de los datos del paquete para la WEB
     *
     * @var request
     * @var key
     * @var $boolPrecio
     * 
     * @return array 
     */

	static public function paqueteWeb($request, $key, $boolPrecio, $guiaId)
	{
		        
		$precioUnitario = 0;
        if ($boolPrecio ){
            $precioUnitario = $request['precio'];
        }
        
		if ( $request['piezas'] === 1 ) {
            $guiaPaqueteInsert = array(
                'peso' => $request['pesos'][$key]
                ,'alto' => $request['altos'][$key]
                ,'ancho' => $request['anchos'][$key]
                ,'largo' => $request['largos'][$key]
                ,'precio_unitario' => $precioUnitario
                ,'guia_id' => $guiaId
            );    
            
        } else {
            if (count($request['pesos']) ===1) {
                $key = 0;
            }
            $guiaPaqueteInsert = array(
                'peso' => $request['pesos'][$key]
                ,'alto' => $request['altos'][$key]
                ,'ancho' => $request['anchos'][$key]
                ,'largo' => $request['largos'][$key]
                ,'precio_unitario' => $precioUnitario
                ,'guia_id' => $guiaId
            );    

        }

        return $guiaPaqueteInsert;
		

	}

	/**
	 * Seccion para funcionalidad getters y setters
	 * 
	 * 
	 */
	
	public function getInsert(){
        return $this->insert;
    }
}

?>