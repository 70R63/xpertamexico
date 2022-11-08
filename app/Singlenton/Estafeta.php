<?php

namespace App\Singlenton;

use GuzzleHttp\Client;
use Log;
use Carbon\Carbon;
use Config;

#CLASES DE NEGOCIO 
use App\Models\LtdSesion;
use App\Dto\EstafetaDTO;


class Estafeta {

    private static $instance;

    private $token;
    private $baseUri;

    public $documento = 0; 
    private $resultado = array();

    public function __construct(int $ltd_id){

        Log::info(__CLASS__." ".__FUNCTION__);
        $this->baseUri = Config('ltd.estafeta.base_uri');
        
        $sesion = LtdSesion::where('ltd_id', $ltd_id)
                ->where('expira_en','>', Carbon::now())
                ->first();

        if (!is_null($sesion)) {
            $this->token = $sesion->token;

        }else {
            Log::info(__CLASS__." ".__FUNCTION__." Seccion Else");
            $this->token = "98ee1a03-72b7-497e-9cfb-179be5339f94";

            $insert = array('empresa_id' => auth()->user()->empresa_id
                ,'ltd_id'   => $ltd_id
                ,'token'    => $this->token
                ,'expira_en'=> Carbon::now()->addMinutes(1440)
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

    public function envio($request){
        Log::info(__CLASS__." ".__FUNCTION__." INICIO");
       

        $dto = new EstafetaDTO();
        $body = $dto->parser($request);

        $client = new Client(['base_uri' => $this->baseUri]);
        $authorization = sprintf("Bearer %s",$this->token);

        $headers = [
            'Authorization' => $authorization
            ,'Content-Type' => 'application/json'
            ,'Accept'    => 'application/json'
            ,'apiKey'   => 'l76a4958a420d244328a2daa8d68740c75'
        ];
        
        $response = $client->request('POST', 'v1/wayBills?outputType=FILE_PDF&outputGroup=REQUEST&responseMode=SYNC_INLINE&printingTemplate=NORMAL_TIPO7_ZEBRAORI', [
            'headers'   => $headers
            ,'body'     => json_encode($body)
        ]);

        $this -> resultado = $response->getBody()->getContents();
            
        Log::info(__CLASS__." ".__FUNCTION__." FIN");
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

    public function getResultado(){
        return $this->resultado;
    }
}

?>