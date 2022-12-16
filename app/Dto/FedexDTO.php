<?php

namespace App\Dto;

use App\Dto\Fedex\Etiqueta;
use App\Dto\Fedex\RequestedShipment;
use App\Dto\Fedex\AccountNumber;
use App\Dto\Fedex\Shipper;
use App\Dto\Fedex\Recipients;
use App\Dto\Fedex\Contact;
use App\Dto\Fedex\Address;
use App\Dto\Fedex\RequestedPackageLineItems;
use App\Dto\Fedex\DeclaredValue;
use App\Dto\Fedex\Weight;

use Log;

/**
 * 
 */
class FedexDTO 
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
	 * Metodo para asignar los valores del request a una etiqueta 
	 * para generar el venvio a la api de Fedex
	 * https://developer.fedex.com/api/en-ph/guides/api-reference.html
	 * 
	 * @param \Illuminate\Http\Request $request
	 * @return App\Dto\Fedex\Etiqueta Etiqueta
	 */

	public function parser($request){
        Log::info(__CLASS__." ".__FUNCTION__." INICIANDO");
		
		
		$weight = new Weight(array('value'=> $request['peso_facturado']));

		$contactShipper = New Contact( 
			array("personName" 	=> $request['contacto']
				,"phoneNumber"	=> $request['celular']
				,"companyName"	=> $request['nombre']) 
			);

		$direccion = sprintf("%s %s,Ext %s,%s",$request['direccion'],$request['no_int'],$request['no_ext'],$request['direccion2'] );
		$streetLines = str_split($direccion,35);

		$addressShipper = New Address(
			array("streetLines"     => $streetLines
				,"city"	=> $request['colonia']
				,"stateOrProvinceCode"	=> $request['entidad_federativa']
				,"postalCode"	=> $request['cp']
			));

		$contactRecipients = New Contact( 
			array("personName" 	=> $request['contacto_d']
				,"phoneNumber"	=> $request['celular_d']
				,"companyName"	=> $request['nombre_d']) 
			);


		$direccion_d = sprintf("%s %s,Ext %s,%s",$request['direccion_d'],$request['no_int_d'],$request['no_ext_d'],$request['direccion2_d'] );
		$streetLines_d = str_split($direccion_d,35);

		$addressRecipients = New Address(
			array("streetLines"     => $streetLines_d
				,"city"	=> $request['colonia_d']
				,"stateOrProvinceCode"	=> $request['entidad_federativa_d']
				,"postalCode"	=> $request['cp_d']
			));

		$shipper = new Shipper(array('contact' => $contactShipper, 'address' => $addressShipper ));

		$recipients = New Recipients(array('contact' => $contactRecipients, 'address' => $addressRecipients ));

		$declaredValueWeight = array('declaredValue' => new DeclaredValue(["amount"=>$request['valor_envio']])
                                    ,'weight' => $weight
                                    ,'groupPackageCount' => $request['piezas'] 
                                    ,'itemDescriptionForClearance' => $request['contenido']
                                );

		$requestedPackageLineItems = New RequestedPackageLineItems($declaredValueWeight);

        $requestedShipment = 
            array('shipper' => $shipper
            ,'recipients' => array($recipients)
            ,'requestedPackageLineItems' => array($requestedPackageLineItems)
            ,'serviceType'		=> Config('ltd.fedex.servicio')[$request['servicio_id']]
        );


        $accountNumber = new AccountNumber([
        		'value' => config('ltd.fedex.cred.accountNumber')
        	]
        );

        $init = array('requestedShipment'   => new RequestedShipment($requestedShipment)
	                    ,'accountNumber'    => $accountNumber );
        
        Log::info(__CLASS__." ".__FUNCTION__." INICIANDO");
        return new Etiqueta($init);

	}
}