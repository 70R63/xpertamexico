<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as Controller;
use Illuminate\Http\Request;
use Log;
use Laravel\Sanctum\HasApiTokens;
use App\Dto\Estafeta;
use GuzzleHttp\Client;

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
        
        /*
        $request = new HttpRequest();
        $request->setUrl('https://apis-sandbox.fedex.com/ship/v1/shipments');
        $request->setMethod(HTTP_METH_POST);

        $request->setHeaders(array(
          'Authorization' => 'Bearer ',
          'X-locale' => 'en_US',
          'Content-Type' => 'application/json'
        ));

        $request->setBody(input); // 'input' refers to JSON Payload
        */
        $response = null;

        try {
            $client = new Client([
                'base_uri' => 'https://apis-sandbox.fedex.com/',
            ]);

            $headers = [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ];

            
            $body = array('grant_type' => 'client_credentials',
                    'client_id' => 'l7640a59a8ce1c4dfea7bb2d302febc882' ,
                    'client_secret' => '2bc10d1d2f3b4b6ab55a0e63518c306e');
            
            $body = "grant_type=client_credentials&client_id=l7640a59a8ce1c4dfea7bb2d302febc882&client_secret=2bc10d1d2f3b4b6ab55a0e63518c306e";


            $response = $client->request('POST', 'oauth/token', [
                'headers'   => $headers
                ,'body'     => $body
                
            ]);

            //return $response;


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
}
