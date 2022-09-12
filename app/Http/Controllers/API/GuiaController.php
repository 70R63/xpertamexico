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

        //Log::info($request);
        $guia = new Estafeta();


        return $guia -> init( $request );        
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
                    'client_id' => 'hDMICPKUl2jbCcTv' ,
                    'client_secret' => 'jKSruOWyMi1i2Q0KLjMcUtub0', );
            
            $body="grant_type=csp_credentials&client_id=hDMICPKUl2jbCcTv&client_secret=jKSruOWyMi1i2Q0KLjMcUtub0";

            $response = $client->request('POST', 'oauth/token', [
                'headers'   => $headers
                ,'body'     => $body
            ]);


/*
            $headers = [
                'Authorization' => 'Bearer ',        
                'Content-Type'=> "application/json",
                'X-locale'=> "en_US",
            ];

            $response = $client->request('POST', 'ship/v1/shipments', [
                'headers' => $headers
            ]);
*/
            Log::debug($response);
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
