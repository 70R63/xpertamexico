<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as Controller;
use Illuminate\Http\Request;
use Log;
use Laravel\Sanctum\HasApiTokens;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;

#CLASES DE NEGOCIO 
use App\Singlenton\Estafeta ;

/**
 * GuiaController
 * Los parametros para los ltds estan definidos desde el comienzo de la contruccion 
 * del sistema.
 * 
 * @param fedex = 1
 * @param estafeta = 2
 *
 * @return \Illuminate\Http\Response
 */
class GuiaController extends Controller
{
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function Creacion(Request $request)
    {
        Log::info(__CLASS__." ".__FUNCTION__);
        $guia = new Estafeta();
        $guia->parser($request,"API");

        return $guia -> init();        
    }


    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function Fedex(Request $request)
    {
        Log::info(__CLASS__." ".__FUNCTION__);

        Log::info($request);
        $response = null;

        try {
            $client = new Client([
                'base_uri' => 'https://apis-sandbox.fedex.com/',
            ]);

            $headers = [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ];

            /*
            $body = array('grant_type' => 'client_credentials',
                    'client_id' => 'l7640a59a8ce1c4dfea7bb2d302febc882' ,
                    'client_secret' => '2bc10d1d2f3b4b6ab55a0e63518c306e');
            */
            $body = "grant_type=client_credentials&client_id=l7640a59a8ce1c4dfea7bb2d302febc882&client_secret=2bc10d1d2f3b4b6ab55a0e63518c306e";


            $response = $client->request('POST', 'oauth/token', [
                'headers'   => $headers
                ,'body'     => $body
                
            ]);

            Log::debug(print_r($response>getBody()->scope,true));
            Log::debug("Fin Response --------------------");
            $resultado = "RESPONSE";
            $mensaje = "LA guia se creo con exito";
            return $this->sendResponse($resultado, $mensaje);
        
            
        } catch (\GuzzleHttp\Exception\ClientException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." ClientException");
            Log::debug(print_r($ex,true));
            
            return $this->sendResponse("Response", "ClientException");
        } catch (\GuzzleHttp\Exception\InvalidArgumentException $ex) {
            Log::debug($ex);
            return $this->sendResponse("Response", "InvalidArgumentException");
        } catch (HttpException $ex) {
          
            $resultado = $ex;
            $mensaje = "La guia no pudo ser creada";
            return $this->sendResponse($resultado, $mensaje);
        }
    }


    /**
     * ESTAFETA CREACION DE GUIA
     * @param body,json con estructura para la guia
     * 
     *
     * @return \Illuminate\Http\Response
     */
    public function estafeta(Request $request){
        Log::info(__CLASS__." ".__FUNCTION__);

        Log::debug($request);
        $response = null;

        try {
            
            $estafeta = new Estafeta(2);

            $estafeta -> envio($request->except(['api_token']));
            $resultado = $singlenton->getResultado();
            
            $mensaje = "LA guia se creo con exito";
            return $this->sendResponse(json_decode($resultado), $mensaje);
        
            
        } catch (\GuzzleHttp\Exception\ClientException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." ClientException");
            Log::debug(print_r($ex,true));
            
            return $this->sendResponse("Response", "ClientException");

        } catch (\GuzzleHttp\Exception\InvalidArgumentException $ex) {
            Log::debug($ex );
            return $this->sendResponse("Response", "InvalidArgumentException");

        } catch (\GuzzleHttp\Exception\ServerException $ex) {
            $response = $ex->getResponse()->getBody()->getContents();
            Log::debug(print_r($response,true));
            Log::debug(print_r(json_decode($response),true));
            return $this->sendResponse(json_decode($response), "ServerException");            

        } catch (HttpException $ex) {
          
            $resultado = $ex;
            $mensaje = "La guia no pudo ser creada";
            return $this->sendResponse($resultado, $mensaje);
        }
    }// Fin public function Estafeta
}
