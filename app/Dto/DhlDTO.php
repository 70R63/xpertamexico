<?php
namespace App\Dto;

use App\Dto\DHL\Label;
use App\Dto\DHL\LabelSeguro;
use App\Dto\DHL\Pickup;
use App\Dto\DHL\Accounts;
use App\Dto\DHL\OutputImageProperties;
use App\Dto\DHL\ImageOption1;
use App\Dto\DHL\ImageOption2;
use App\Dto\DHL\AddressDetails;
use App\Dto\DHL\PostalAddress;
use App\Dto\DHL\ContactInformation;
use App\Dto\DHL\Content;
use App\Dto\DHL\ContentSeguro;
use App\Dto\DHL\CustomerReferences;
use App\Dto\DHL\Packages;
use App\Dto\DHL\Dimensions;
use App\Dto\DHL\ValueAddedServices;


use Log;
use Carbon\Carbon;
use Config;

/**
 * 
 */
class DhlDTO 
{
    /**
     * Insert , array que conse usara para insertar como si fura un request
     *
     * @var nombre
     */
    public $insert;
    private $rangoExcedido = false;
    private $body = array();
      
    function __construct()
    {
        // code...
    }

    /**
     * Metodo para asignar los valores del request a una etiqueta 
     * para generar el envio a la api de dhl
     * https://developer.dhl.com/api-reference/dhl-express-mydhl-api#operations-shipment-exp-api-shipments
     * 
     * @param \Illuminate\Http\Request $request
     * @return array body
     */

    public function parser($request){
    	Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);

    	$pickup = new Pickup();
    	$accounts = new Accounts();
        

    	$imageOption1 = new ImageOption1();
    	$imageOption2 = new ImageOption2();

    	$imageOptions = array("imageOptions" => array($imageOption1, $imageOption2));
    	$outputImageProperties = new OutputImageProperties($imageOptions);

        //Origen
        $addressLine1 = sprintf("%s %s %s",$request["direccion"], $request["no_int"], $request["no_ext"] );
    	$postalAddress = new PostalAddress(
                array("cityName"    => $request["ciudad"]
                    ,"postalCode"   => $request["cp"]
                    ,"addressLine1" => $addressLine1
                )
            );
    	$contactInformation = new ContactInformation(
                            array("phone" => $request["celular"]
                                ,"companyName" => $request["nombre"]
                                ,"fullName" => $request["contacto"]
                            )
                        );
    	$shipperDetails = array('postalAddress' => $postalAddress
    					, 'contactInformation' => $contactInformation);

        //Destino
        $addressLine1 = sprintf("%s %s %s",$request["direccion_d"], $request["no_int_d"], $request["no_ext_d"] );
    	$postalAddress = new PostalAddress(
                array("cityName"    => $request["ciudad_d"]
                    ,"postalCode"   => $request["cp_d"]
                    ,"addressLine1" => $addressLine1
                )
            );
    	$contactInformation = new ContactInformation(
                            array("phone" => $request["celular_d"]
                                ,"companyName" => $request["nombre_d"]
                                ,"fullName" => $request["contacto_d"]
                            )
                        );

    	$receiverDetails = array('postalAddress' => $postalAddress
    					, 'contactInformation' => $contactInformation);

    	$customerDetails = new AddressDetails( 
    				array('shipperDetails' => $shipperDetails
    					, 'receiverDetails' => $receiverDetails)
    			);


        $packages = $this->paquetes($request);

    	

        $plannedShipping = sprintf("%s GMT-06:00", Carbon::now()->addHours(24)->format('Y-m-d\TH:i:s') );

        $productCode = Config('ltd.dhl.servicio')[$request['servicio_id']];

        

        if ($request['bSeguro']) {

            $content = new Content(array("packages" => $packages
                                //,"declaredValue" => (float)$request['valor_envio']
                            )
                        );

            $valueAddedServices = new ValueAddedServices();
            $valueAddedServices->value = (float)$request['valor_envio'];

            $this->body = new LabelSeguro(
                array("productCode"=> $productCode
                    ,"plannedShippingDateAndTime" => $plannedShipping
                    ,"pickup"=> $pickup
                    ,"accounts"=> array($accounts)
                    ,"outputImageProperties" => $outputImageProperties
                    ,"customerDetails"  => $customerDetails
                    ,"content"  => $content
                    ,"valueAddedServices"  => array($valueAddedServices)
                )
            );  
        } else{
            $content = new Content(array('packages' => $packages
                            )
                        );
            $this->body = new Label(
                array("productCode"=> $productCode
                    ,"plannedShippingDateAndTime" => $plannedShipping
                    ,"pickup"=> $pickup
                    ,"accounts"=> array($accounts)
                    ,"outputImageProperties" => $outputImageProperties
                    ,"customerDetails"  => $customerDetails
                    ,"content"  => $content
                )
            );    
        }
    	
    	Log::debug(print_r(json_encode($this->body),true));
    	Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
    }


    /**
     * Metodo para asignar los valores del request a una etiqueta 
     * para generar el envio a la api de dhl
     * https://developer.dhl.com/api-reference/dhl-express-mydhl-api#operations-shipment-exp-api-shipments
     * 
     * @param \Illuminate\Http\Request $request
     * @return array body
     */

    private function paquetes($request){

        $customerReferences = new CustomerReferences();


        foreach ($request["pesos"] as $key => $value) {

            $dimensions = new Dimensions(
                    array("length" => (float)$request["largos"][$key]
                        ,"width" => (float)$request["anchos"][$key]
                        ,"height" => (float)$request["altos"][$key]
                    )
                );

            $package = new Packages(
                    array("customerReferences" => array($customerReferences) 
                        ,"weight" => (float)$value
                        ,"dimensions" => $dimensions
                    )
                );
            $packages[] = $package;
        }
        
        if ($request['piezas'] > count($request['pesos']) ) {
            for ($i=1; $i < $request['piezas']; $i++) { 
                $packages[] = $packages[0];
            }
        }
            
        return $packages;
        
    }

    public function getBody(){
        return $this->body;
    }
}