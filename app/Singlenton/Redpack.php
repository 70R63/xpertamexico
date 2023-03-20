<?php

namespace App\Singlenton;

use GuzzleHttp\Client;
use Log;
use Carbon\Carbon;
use Config;
//use App\Models\Servicio;

use App\Models\LtdSesion;

/**
* Singlenton para contriuir una peticion de creacion de guia y validacion del token
* 
* @param string $token
* @param string $baseUri
*  
*/

class Redpack {

    private static $instance;

    private $token;
    private $baseUri;

    public $documento = 0; 
    private $trackingNumber = 0;
    private $scanEvents = array();
    private $paquete = array();
    private $exiteSeguimiento = false;
    private $quienRecibio = "No entregado aun"; 
    private $latestStatusDetail;
    private $fechaEntrega; //Validar uso en la case redpack y no en el controller
    private $pickupFecha;


    public function __construct($empresa_id = 1){

        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        $sesion = LtdSesion::where('ltd_id', Config('ltd.redpack.id'))
                ->where('expira_en','>', Carbon::now())
                ->first();

        if (!is_null($sesion)) {
        	Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
            $this->token = $sesion->token;

        }else {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);

            $base64 = base64_encode(Config('ltd.redpack.client_id').":".Config('ltd.redpack.client_secret'));
            $client = new Client(['base_uri' => Config('ltd.redpack.base_uri_token') ]);
            
            $headers = [ 'Content-Type' => 'application/x-www-form-urlencoded'
                        ,'Authorization'=> "Basic ".$base64 
                        ];

            
            Log::info(__CLASS__." ".__FUNCTION__." Token para etiquetas");
            $formParams = [
            	'grant_type' => 'password'
                ,'username'		=> Config('ltd.redpack.user')
                ,'password'		=> Config('ltd.redpack.pass')
                ,'scope' => ''
            ];
                
           
           	Log::debug(print_r($formParams,true));
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
            $response = $client->request('post', '/oauth/token',
                ['form_params' => $formParams
                	, 'headers'     => $headers
            	]
            );

            $contenido = json_decode($response->getBody()->getContents());
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
            Log::debug(print_r($contenido,true));
        
            $this->token = $contenido->access_token;

            $insert = array('empresa_id' => $empresa_id
                ,'ltd_id'   => Config('ltd.redpack.id')
                ,'token'    => $this->token
                ,'expira_en'=> Carbon::now()->addSeconds($contenido->expires_in)
                 );

            $id = LtdSesion::create($insert)->id;
            Log::info(__CLASS__." ".__FUNCTION__." ID LTD SESION $id");

        }
        
    }


    /**
     * Cliente busca crear una funcion donde se inicialice la peticion via guzzle.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return GuzzleHttp\Client $response
     */

    private function clienteRest(array $body,$metodo = 'GET', $servicio, array $headers){
        Log::debug(__CLASS__." ".__FUNCTION__." ".__LINE__." INICIANDO-----------------");

        $client = new Client(['base_uri' => $this->baseUri]);
        

        $bodyJson = json_encode($body);
        Log::debug(print_r($bodyJson,true));
        
        Log::debug(__CLASS__." ".__FUNCTION__." FINALIZANDO-----------------");
        return $client->request($metodo,$servicio , [
                    'headers'   => $headers
                    ,'body'     => $bodyJson
                ]);
    }


   /**
     * documentation es la actividad para registrar nuestra peticion.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function documentation($body){
        Log::debug(__CLASS__." ".__FUNCTION__." ".__LINE__);
        $authorization = sprintf("Bearer %s",$this->token);

        $headers = ['Authorization' => $authorization  
                    ,'Content-Type' => 'application/json'
                ];

        $this->baseUri = Config('ltd.redpack.uri_documentation');

        $response = $this->clienteRest($body, 'POST','redpack/documentation', $headers);

        Log::debug(__CLASS__." ".__FUNCTION__." response ");
        $contenido = json_decode($response->getBody()->getContents());
        
        //Log::debug(print_r($contenido[0],true) );
        $objResponse = $contenido[0];

        $this->trackingNumber = $objResponse->trackingNumber;
        $this->documento = $objResponse->parcels;
        
        Log::debug(__CLASS__." ".__FUNCTION__." ".__LINE__);
       
    }



    public function getTrackingNumber(){
        return $this->trackingNumber;
    }

    public function getScanEvents(){
        return $this->scanEvents;
    }

    public function getPaquete(){
        return $this->paquete;
    }

    public function getExiteSeguimiento(){
        return $this->exiteSeguimiento;
    }

    public function getQuienRecibio(){
        return $this->quienRecibio;
    }

    public function getLatestStatusDetail(){
        return $this->latestStatusDetail;
    }

    public function getFechaEntrega(){
        return $this->fechaEntrega;
    }

    public function getPickupFecha(){
        return $this->pickupFecha;
    }

    public function getDocumento(){
        return $this->documento;
    }
}