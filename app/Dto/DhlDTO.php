<?php
namespace App\Dto;

use App\Dto\DHL\Label;
use App\Dto\DHL\Pickup;
use App\Dto\DHL\Accounts;
use App\Dto\DHL\OutputImageProperties;
use App\Dto\DHL\ImageOption1;
use App\Dto\DHL\ImageOption2;
use App\Dto\DHL\AddressDetails;
use App\Dto\DHL\PostalAddress;
use App\Dto\DHL\ContactInformation;
use App\Dto\DHL\Content;
use App\Dto\DHL\CustomerReferences;
use App\Dto\DHL\Packages;
use App\Dto\DHL\Dimensions;


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
        $imageOption2->numberOfCopies = (int) $request['piezas'];


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


        $customerReferences = new CustomerReferences();


        $length = 0;
        foreach ($request["largos"] as $key => $value) {
            $length = $length + $value;
        }

        $width = 0;
        foreach ($request["anchos"] as $key => $value) {
            $width = $width + $value;
        }

        $height = 0;
        foreach ($request["altos"] as $key => $value) {
            $height = $height + $value;
        }
        $dimensions = new Dimensions(
                    array("length" => $length
                        ,"width" => $width
                        ,"height" => $height
                    )
                );

        $packages = new Packages(
                        array("customerReferences" => array($customerReferences) 
                            ,"weight" => (float)$request["peso_facturado"]
                            ,"dimensions" => $dimensions
                        )
                    );

    	$content = new Content(array('packages' => array($packages)
                               // ,"declaredValue"=> (float)$request["costo_seguro"]
                            )
                        );

        $plannedShipping = sprintf("%s GMT-06:00", Carbon::now()->addHours(24)->format('Y-m-d\TH:i:s') );

        $productCode = Config('ltd.dhl.servicio')[$request['servicio_id']];
    	$this->body = new Label(
    		array("productCode"=> $productCode
                ,"plannedShippingDateAndTime" => $plannedShipping
                ,"pickup"=> $pickup
    			,"accounts"=> array($accounts)
    			,"outputImageProperties" => $outputImageProperties
    			,"customerDetails"	=> $customerDetails
    			,"content"	=> $content
    		)
    	);

    	Log::debug(print_r(json_encode($this->body),true));
    	Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
    }


    public function getBody(){
        return $this->body;
    }
}