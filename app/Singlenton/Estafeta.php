<?php

namespace App\Singlenton;

use GuzzleHttp\Client;
use Log;
use Carbon\Carbon;
use Config;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Http;

#CLASES DE NEGOCIO 
use App\Models\LtdSesion;

class Estafeta {

    private static $instance;

    private $token;
    private $baseUri;

    public $documento = 0; 
    private $resultado = array();
    private $trackingNumber = "trackingNumber";
    private $exiteSeguimiento = false;
    private $quienRecibio = "No entregado aun";
    private $paquete = array();
    private $latestStatusDetail;
    private $ultimaFecha;

    public function __construct(int $ltd_id, $empresa_id= 1, $plataforma = 'WEB',int $servicioID = 1){

        Log::info(__CLASS__." ".__FUNCTION__);
        $this->baseUri = Config('ltd.estafeta.base_uri');
        
        if ($plataforma == 'WEB'){
            $empresa_id = auth()->user()->empresa_id;
        } 
        
        $sesion = LtdSesion::where('ltd_id', $ltd_id)
                ->where('servicio',$servicioID)
                ->where('empresa_id',$empresa_id)
                ->where('expira_en','>', Carbon::now())
                ->first();

        if (!is_null($sesion)) {
            Log::info(__CLASS__." ".__FUNCTION__." Token existente");
            $this->token = $sesion->token;

        }else {
            Log::info(__CLASS__." ".__FUNCTION__." Seccion Else");


            $client = new Client(['base_uri' => Config('ltd.estafeta.token_uri') ]);
            $headers = ['Content-Type' => 'application/x-www-form-urlencoded'];

            if ($servicioID === 1) {
                Log::info(__CLASS__." ".__FUNCTION__." Token para etiquetas");
                $formParams = [
                    'client_id' => Config('ltd.estafeta.api_key'),
                    'client_secret' => Config('ltd.estafeta.secret'),
                    'grant_type' => 'client_credentials'
                    ,'scope' => 'execute'
                ];
            } else {
                Log::info(__CLASS__." ".__FUNCTION__." Token para rastreo");
                $formParams = [
                    'client_id' => Config('ltd.estafeta.rastreo.api_key'),
                    'client_secret' => Config('ltd.estafeta.rastreo.secret'),
                    'grant_type' => 'client_credentials'
                    ,'scope' => 'execute'
                ];
            }
            

            $response = $client->request('POST', 'auth/oauth/v2/token',
                ['form_params' => $formParams
                , 'headers'     => $headers]
            );

            if ($response->getStatusCode() == "200"){
                $json = json_decode($response->getBody()->getContents());

                $this->token = $json->access_token;

                $insert = array('empresa_id' => $empresa_id
                    ,'ltd_id'   => $ltd_id
                    ,'token'    => $this->token
                    ,'servicio'    => $servicioID
                    ,'expira_en'=> Carbon::now()->addMinutes(1380)
                     );
                Log::debug(print_r($insert,true));
                $id = LtdSesion::create($insert)->id;
                Log::info(__CLASS__." ".__FUNCTION__." ID LTD SESION $id");
            }
            
        }
        
    }

    /**
     * clienteRest busca crear una funcion donde se inicialice la peticion via guzzle.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return GuzzleHttp\Client $response
     */

    private function clienteRest(array $body,$metodo = 'GET', string $baseUri, $servicio, int $servicioID=1){
        Log::debug(__CLASS__." ".__FUNCTION__." INICIANDO-----------------");
        $client = new Client(['base_uri' => $baseUri]);
        $authorization = sprintf("Bearer %s",$this->token);

        $apiKey = ($servicioID === 1) ? Config('ltd.estafeta.api_key') : Config('ltd.estafeta.rastreo.api_key');
        $headers = ['Authorization' => $authorization
                    ,'Content-Type' => 'application/json'
                    ,'charset' => 'utf-8'
                    ,'apiKey'   => $apiKey
                ];

        $bodyJson = json_encode($body);
        Log::debug(print_r($bodyJson,true));
        
        Log::debug(__CLASS__." ".__FUNCTION__." FINALIZANDO-----------------");
        return $client->request($metodo,$servicio , [
                    'headers'   => $headers
                    ,'body'     => $bodyJson
                ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  array  $body
     * @return \Illuminate\Http\Response
     */

    public function envio($body){
        Log::info(__CLASS__." ".__FUNCTION__." INICIO ------------------");
        
        $client = new Client(['base_uri' => $this->baseUri]);
        $authorization = sprintf("Bearer %s",$this->token);

        $headers = [
            'Authorization' => $authorization
            ,'Content-Type' => 'application/json'
            ,'Accept'    => 'application/json'
            ,'apiKey'   => Config('ltd.estafeta.api_key')
        ];
        Log::debug(print_r("Armando Peticion",true));

        $response = $client->request('POST', 'v1/wayBills?outputType=FILE_PDF&outputGroup=REQUEST&responseMode=SYNC_INLINE&printingTemplate=NORMAL_TIPO7_ZEBRAORI', [
            'headers'   => $headers
            ,'body'     => json_encode($body)
        ]);

        $this -> resultado = json_decode($response->getBody()->getContents());

        $this->documento = $this->resultado->data;
        $this->trackingNumber = $this->resultado->labelPetitionResult->result->description;
        Log::info(__CLASS__." ".__FUNCTION__." FIN ------------------");
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  array  $body
     * @return \Illuminate\Http\Response
     */

    public function rastreo($trackingNumber = 1,)
    {
        Log::info(__CLASS__." ".__FUNCTION__." INICIO ------------------");

        $pesoDimension  = array('peso' => 0
                    , 'largo' => 0
                    , 'ancho' => 0
                    , 'alto' => 0
                );

        $body = array (
          'suscriberId' => Config('ltd.estafeta.rastreo.suscriberId'),
          'login' => Config('ltd.estafeta.rastreo.login'),
          'password' => Config('ltd.estafeta.rastreo.pswd'),
          'searchType' => array (
            'type' => 'L',
            'waybillList' => array (
                'waybillType' => 'G',
                'waybills' => array (
                    'string' => array (
                        0 => $trackingNumber,
                    ),
                ),
            ),
          ),
          'searchConfiguration' => array (
            'historyConfiguration' => array (
              'historyType' => 'all',
              'includeHistory' => true,
            ),
            'includeCustomerInfo' => true,
            'includeDimensions' => true,
            'includeInternationalData' => true,
            'includeMultipleServiceData' => true,
            'includeReturnDocumentData' => true,
            'includeSignature' => true,
            'includeWaybillReplaceData' => true,
          ),
        );

        $response = $this->clienteRest($body, 'POST',Config('ltd.estafeta.rastreo.base_uri'),Config('ltd.estafeta.rastreo.servicio'), 2);

        #Log::debug(print_r($response->getBody()->getContents(),true));
        $tmp = $response->getBody()->getContents();
        Log::debug(print_r($tmp,true));
        $contenido = json_decode($tmp);
        $response = $contenido->ExecuteQueryResponse->ExecuteQueryResult->trackingData;
        
        if (isset($response->TrackingData)) {
            Log::info("Existe tracking");

            $trackingData = $response->TrackingData;
            
            Log::info(__CLASS__." ".__FUNCTION__." Ultimo estatus");
            $this->latestStatusDetail = $trackingData->statusENG;
            Log::debug(print_r($this->latestStatusDetail,true));
            if (isset($trackingData->dimensions->weight)) {
                $weight = $trackingData->dimensions->weight;
                $volumetricWeight = $trackingData->dimensions->volumetricWeight;

                $pesoDimension['largo'] = $trackingData->dimensions->length;
                $pesoDimension['ancho'] = $trackingData->dimensions->width;
                $pesoDimension['alto'] = $trackingData->dimensions->height;
                $pesoDimension['peso'] = ( $weight > $volumetricWeight) ? $weight : $volumetricWeight;
            }
            
            $this->paquete = $pesoDimension;

            $receiverName = explode(":", $trackingData->deliveryData->receiverName);
            $this->quienRecibio = ( count($receiverName) === 2) ? $receiverName[1] : "No entregado aun" ;

            Log::info(__CLASS__." ".__FUNCTION__." Ultimo estatus");
            if ($this->latestStatusDetail === "DELIVERED") {
                $ultimaFecha = $trackingData->deliveryData->deliveryDateTime;
            }else{
                $ultimaFecha = Carbon::now();
                if ( isset($trackingData->history->History)) {
                    $evento = count($trackingData->history->History)-1;
                    $ultimoEvento = $trackingData->history->History[$evento];
                    $ultimaFecha = $ultimoEvento->eventDateTime;
                }
                
            }
            $this->ultimaFecha = Carbon::parse($ultimaFecha)->format('Y-m-d H:i:s');
            
            Log::debug(print_r($this->ultimaFecha,true));
            
            $this->exiteSeguimiento = true;
        }else{
            Log::debug("Sin tracking");
            $this->exiteSeguimiento = false;   
        }

        Log::info(__CLASS__." ".__FUNCTION__." FIN ------------------");
    }


    public static function getInstance( int $ltd_id, $empresaId = 1,$plataforma = "WEB", $servicioID=1 ){
        if (!self::$instance) {
            Log::debug(__CLASS__." ".__FUNCTION__." Creando intancia");
            self::$instance = new self($ltd_id, $empresaId, $plataforma, $servicioID);
        }
        Log::debug(__CLASS__." ".__FUNCTION__." return intancia");
        return self::$instance;
    }

    public function setToken($value){
        $this->token = $value;
    }

    public function getToken(){
        return $this->token;
    }

    public function getResultado(){
        return $this->resultado;
    }

    public function getTrackingNumber(){
        return $this->trackingNumber;
    }

    public function getExiteSeguimiento(){
        return $this->exiteSeguimiento;
    }

    public function getQuienRecibio(){
        return $this->quienRecibio;
    }

    public function getPaquete(){
        return $this->paquete;
    }

    public function getLatestStatusDetail(){
        return $this->latestStatusDetail;
    }

    public function getUltimaFecha(){
        return $this->ultimaFecha;
    }
}

?>