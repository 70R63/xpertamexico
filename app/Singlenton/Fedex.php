<?php

namespace App\Singlenton;

use GuzzleHttp\Client;
use Log;
use Carbon\Carbon;
use Config;

use App\Models\LtdSesion;


class Fedex {

    private static $instance;

    private $token;
    private $baseUri;

    public $documento = 0; 
    private $trackingNumber = 0; 

    private function __construct(int $ltd_id){

        Log::info(__CLASS__." ".__FUNCTION__);
        $this->baseUri = Config('ltd.fedex.base_uri');
        
        $sesion = LtdSesion::where('ltd_id', $ltd_id)
                ->where('expira_en','>', Carbon::now())
                ->first();

        if (!is_null($sesion)) {
            $this->token = $sesion->token;

        }else {
            Log::info(__CLASS__." ".__FUNCTION__." Seccion Else");
            $client = new Client(['base_uri' => $this->baseUri]);

            $headers = ['Content-Type' => 'application/x-www-form-urlencoded'];
                
            $body = "grant_type=client_credentials&client_id=l7640a59a8ce1c4dfea7bb2d302febc882&client_secret=2bc10d1d2f3b4b6ab55a0e63518c306e";

            $response = $client->request('POST', 'oauth/token', [
                    'headers'   => $headers
                    ,'body'     => $body
                ]);

            $contenido = json_decode($response->getBody()->getContents());

            Log::debug(print_r($contenido,true));

            $this->token = $contenido->access_token;

            $insert = array('empresa_id' => auth()->user()->empresa_id
                ,'ltd_id'   => $ltd_id
                ,'token'    => $this->token
                ,'expira_en'=> Carbon::now()->addSeconds($contenido->expires_in)
                 );

            $id = LtdSesion::create($insert)->id;
            Log::info(__CLASS__." ".__FUNCTION__." ID LTD SESION $id");
        }
        
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function envio($body){

            $client = new Client(['base_uri' => $this->baseUri]);
            $authorization = sprintf("Bearer %s",$this->token);

            $headers = ['Authorization' => $authorization
                        ,'X-locale' => 'en_US'
                        ,'Content-Type' => 'application/json'];
            
            $response = $client->request('POST', 'ship/v1/shipments', [
                        'headers'   => $headers
                        ,'body'     => $body
                    ]);

            $contenido = json_decode($response->getBody()->getContents());
            
            $transactionShipments = $contenido->output->transactionShipments[0];

            $pieceResponses = $transactionShipments->pieceResponses[0];
            $packageDocuments = $pieceResponses->packageDocuments[0];

            $this->documento = $packageDocuments->url;
            $this->trackingNumber = $transactionShipments->masterTrackingNumber;
            Log::debug(print_r($contenido->output,true));
            //Log::debug(print_r($transactionShipments->masterTrackingNumber ,true));

    }

    public static function getInstance( int $ltd_id){
        if (!self::$instance) {
            Log::debug(__CLASS__." ".__FUNCTION__." Creando intancia");
            self::$instance = new self($ltd_id);
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


    public function getTrackingNumber(){
        return $this->trackingNumber;
    }
}

?>