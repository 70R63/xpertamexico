<?php

namespace App\Http\Controllers\API\Ltd;

use App\Http\Controllers\API\ApiController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Log;
use Carbon\Carbon;

use App\Negocio\Guias\EstafetaCreacion as nEstafetaCreacion;
use App\Negocio\Guias\EstafetaRastreo as nEstafetaRastreo;


use GuzzleHttp\Exception\ConnectException as GuzzleConnectException;


 /**
     * Controlador para la API, para usar el modelo de Negocio.
     * 
     * @author Javier Hernandez
     * @copyright 2022-2023 XpertaMexico
     * @package App\Http\Controllers\API\Ltd
     * @api
     * 
     * @version 1.0.0
     * 
     * @since 1.0.0 Primera version de la funcion fedexApi
     * 
     * @throws
     *
     * @param  \App\Http\Requests\UpdatesucursalRequest  $request Recibe la paticion del cliente
     * 
     * @var array $data Se convierte el Json de la peticion a array
     * 
     * 
     * @return json Objeto con la respuesta de exito o fallo 
     */

class EstafetaController extends ApiController
{

    public function __construct()
    {
        // Constructor method for initialization
        $this->numeroSolicitud = Carbon::now()->timestamp;; 
    }
    
     /**
     * Se busca obtener las tarifas de FEDEX basado en el KG .
     * 
     * @author Javier Hernandez
     * @copyright 2022-2023 XpertaMexico
     * @package App\Negocio\Guias
     * @api
     * 
     * @version 1.0.0
     * 
     * @since 1.0.0 Primera version de la funcion cotizacionDEV
     * 
     * @throws
     *
     * @param  Illuminate\Http\Request  $request Recibe la paticion del cliente
     * 
     * @var array $data Se convierte el Json de la peticion a array
     * @var class $nCreacion Clase para el desarrollo del caso de uso 
     * @var array $response Usado para obteenr la respues del servcvio REST de FEDEX
     * 
     * 
     * @return json Objeto con la respuesta de exito o fallo 
     */

    public function creacionDEV(Request $request){
        try{

            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
             $data =$request->all();
                if(empty($data))
                    throw ValidationException::withMessages(array("Favor de validar tu body"));
            $data['ltd_id'] = 2;
            $data['esManual']="API";
            $data['numero_solicitud'] = Carbon::now()->timestamp;
           
            $servicio = $request->route()->parameter('servicio');
            
            $nEstafetaCreacion = new nEstafetaCreacion();
            switch ($servicio) {
                case 'terrestre':
                    $data['servicio_id']=1; 
                    
                    break;
                case 'diasig':
                    $data['servicio_id']=2;
                    break;
                case '2dias':
                    $data['servicio_id']=3;
                break;

                case 'terrestrepps':
                    $data['servicio_id']=12;
                break;
                case 'diasigpps':
                    $data['servicio_id']=13;
                    break;
                
                default:
                    throw ValidationException::withMessages(array("Favor de validar tu servicio"));
                    break;
               
            }
            $nEstafetaCreacion->parseoApiDev($data);
            
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
            return $this->successResponse( $nEstafetaCreacion->getResponse(), $nEstafetaCreacion->getNotices());


        } catch (ValidationException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__.__LINE__." ValidationException");
            Log::debug(print_r($ex->getMessage(),true));
            return $this->sendError("ValidationException",$ex->getMessage(), "400");
        
        } catch (ModelNotFoundException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__.__LINE__." ModelNotFoundException");
            Log::debug(print_r($ex->getMessage(),true));
            return $this->sendError("ModelNotFoundException","Favor de contactar al proveedor", "400");
            
        } catch (\Spatie\DataTransferObject\DataTransferObjectError $ex) {
            Log::info(__CLASS__." ".__FUNCTION__.__LINE__." DataTransferObjectError");
            Log::debug(print_r($ex->getMessage(),true));
            return $this->sendError("DataTransferObjectError", "consulte con su proveedor", $ex->getMessage(), "400" );

        } catch (\GuzzleHttp\Exception\ClientException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." GuzzleHttp\Exception\ClientException");
            $response = $ex->getResponse()->getBody()->getContents();
            Log::debug(print_r($response,true));
            
            return $this->sendError("LTD ClientException",$response, "400");

        } catch (\GuzzleHttp\Exception\InvalidArgumentException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__.__LINE__." InvalidArgumentException");
            Log::debug(print_r($ex->getMessage(),true));
            return $this->sendError("InvalidArgumentException",$ex->getMessage(), "400");

        } catch (\GuzzleHttp\Exception\ServerException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__.__LINE__." ServerException");
            $response = $ex->getResponse()->getBody()->getContents();
            Log::debug(print_r($response,true));
            Log::debug(print_r(json_decode($response),true));
            return $this->sendError("ServerException",$ex->getMessage(), "400");            

        } catch (\ErrorException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." ErrorException");
            Log::debug(print_r($ex,true));
            
            $mensaje =$ex->getMessage();
            return $this->sendError("ErrorException",$ex->getMessage(), "400");

        } catch (\HttpException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." HttpException");
            $resultado = $ex;
            return $this->sendError("HttpException ",$ex->getMessage(), "400");
        } catch (\Exception $e) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." Exception");
            Log::debug($e->getMessage());
            return $this->sendError("Exception","Favor de validar con tu porveedor", "400");
        }
    }//fin function



    /**
     * Se busca obtener las tarifas de FEDEX basado en el KG .
     * 
     * @author Javier Hernandez
     * @copyright 2022-2023 XpertaMexico
     * @package App\Http\Controllers\API\Ltd
     * @api
     * 
     * @version 1.0.0
     * 
     * @since 1.0.0 Primera version de la funcion cotizacionDEV
     * 
     * @throws
     *
     * @param  Illuminate\Http\Request  $request Recibe la paticion del cliente
     * 
     * @var array $data Se convierte el Json de la peticion a array
     * @var class $nCreacion Clase para el desarrollo del caso de uso 
     * @var array $response Usado para obteenr la respues del servcvio REST de ESTAFETA
     * 
     * 
     * @return json Objeto con la respuesta de exito o fallo 
     */

    public function creacion(Request $request){
        try{

            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
             $data =$request->all();
                if(empty($data))
                    throw ValidationException::withMessages(array("Favor de validar tu body"));
            $data['ltd_id'] = 2;
            $data['esManual']="API";
            $data['numero_solicitud'] = Carbon::now()->timestamp;
            $servicio = $request->route()->parameter('servicios');
            
            $formatoImpresion = $request->route()->parameter('formatoImpresion');
            
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
            switch ($servicio) {
                case 'terrestre':
                    $data['servicio_id']=1; 
                    
                    break;
                case 'diasig':
                    $data['servicio_id']=2;
                    break;
                case '2dias':
                    $data['servicio_id']=3;
                break;
                case 'terrestrepps':
                    $data['servicio_id']=12;
                break;
                case 'diasigpps':
                    $data['servicio_id']=13;
                    break;
                
                default:
                    throw ValidationException::withMessages(array("Favor de validar tu servicio"));
                    break;
               
            }

            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
            switch ($formatoImpresion) {
                case 'termica':
                    $data['formatoImpresion']= "FILE_THERMAL_SEQUENCE"; 
                    break;
                
                default:
                    $data['formatoImpresion']= "FILE_PDF"; 
                    break;
               
            }

            $objetoGeneral = null;
            
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ".$data['numero_solicitud']);
            $data['ltd_id']= 2;
            $nEstafetaCreacion = new nEstafetaCreacion();
            $nEstafetaCreacion->parseoApi($data);
            $objetoGeneral = $nEstafetaCreacion;

            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ".$data['numero_solicitud']);
            return $this->successResponse( $objetoGeneral->getResponse(), $objetoGeneral->getNotices());


        } catch (ValidationException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ".$data['numero_solicitud']." ValidationException");
            Log::debug(print_r($ex->getMessage(),true));
            return $this->sendError("ValidationException",$ex->getMessage(), "400");
        
        } catch (ModelNotFoundException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__.__LINE__." ModelNotFoundException");
            Log::debug(print_r($ex->getMessage(),true));
            return $this->sendError("ModelNotFoundException","Favor de contactar al proveedor", "400");
            
        } catch (\Spatie\DataTransferObject\DataTransferObjectError $ex) {
            Log::info(__CLASS__." ".__FUNCTION__.__LINE__." DataTransferObjectError");
            Log::debug(print_r($ex->getMessage(),true));
            return $this->sendError("DataTransferObjectError", "consulte con su proveedor", $ex->getMessage(), "400" );

        } catch (\GuzzleHttp\Exception\ClientException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." GuzzleHttp\Exception\ClientException");
            $response = $ex->getResponse()->getBody()->getContents();
            Log::debug(print_r($response,true));
            
            return $this->sendError("LTD ClientException",$response, "400");

        } catch (\GuzzleHttp\Exception\InvalidArgumentException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__.__LINE__." InvalidArgumentException");
            Log::debug(print_r($ex->getMessage(),true));
            return $this->sendError("InvalidArgumentException",$ex->getMessage(), "400");

        } catch (\GuzzleHttp\Exception\ServerException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__.__LINE__." ServerException");
            $response = $ex->getResponse()->getBody()->getContents();
            Log::debug(print_r($response,true));
            Log::debug(print_r(json_decode($response),true));
            return $this->sendError("ServerException",$ex->getMessage(), "400");

        } catch (GuzzleConnectException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." GuzzleConnectException ".$ex->getMessage() );
            Log::debug(print_r($ex,true));
            return $this->sendError("Connect Exception","No se pudo contactar al LTD, intente nuevamente", "503");                

        } catch (\ErrorException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." ErrorException");
            Log::debug(print_r($ex,true));
            
            $mensaje =$ex->getMessage();
            return $this->sendError("ErrorException",$ex->getMessage(), "400");

        } catch (\HttpException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." HttpException");
            $resultado = $ex;
            return $this->sendError("HttpException ",$ex->getMessage(), "400");
        } catch (\Exception $e) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." Exception");
            Log::debug($e->getMessage());
            Log::debug($e);
            return $this->sendError("Exception","Favor de validar con tu proveedor", "400");
        }
    }//fin function


    /**
     * Se busca obtener el estado de una guia  .
     * 
     * @author Javier Hernandez
     * @copyright 2022-2024 XpertaMexico
     * @package App\Http\Controllers\API\Ltd
     * @api
     * 
     * @version 1.0.0
     * 
     * @since 1.0.0 Primera version de la funcion rastreo
     * 
     * @throws ValidationException
     *
     * @param  Illuminate\Http\Request  $request Recibe la paticion del cliente
     * 
     * @var array $data Se convierte el Json de la peticion a array
     * @var class $nCreacion Clase para el desarrollo del caso de uso 
     * @var array $response Usado para obteenr la respues del servcvio REST de ESTAFETA
     * 
     * 
     * @return json Objeto con la respuesta de exito o fallo 
     */

    public function rastreoManual(Request $request){
        try{

            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
             $data =$request->all();
                if(empty($data))
                    throw ValidationException::withMessages(array("Favor de validar tu body"));
            $data['ltd_id'] = 2;
            $data['tracking_number']  = $request->route()->parameter('id');
            
            $objetoGeneral = null;
            
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);

            $nEstafetaCreacion = new nEstafetaRastreo();
            $nEstafetaCreacion->parseoRastreoManual($data);
            $objetoGeneral = $nEstafetaCreacion;

            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
            return $this->successResponse( $objetoGeneral->getResponse(), $objetoGeneral->getNotices());


        } catch (ValidationException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__.__LINE__." ValidationException");
            Log::debug(print_r($ex->getMessage(),true));
            return $this->sendError("ValidationException",$ex->getMessage(), "400");
        
        } catch (ModelNotFoundException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__.__LINE__." ModelNotFoundException");
            Log::debug(print_r($ex->getMessage(),true));
            return $this->sendError("ModelNotFoundException","Favor de contactar al proveedor", "400");
            
        } catch (\Spatie\DataTransferObject\DataTransferObjectError $ex) {
            Log::info(__CLASS__." ".__FUNCTION__.__LINE__." DataTransferObjectError");
            Log::debug(print_r($ex->getMessage(),true));
            return $this->sendError("DataTransferObjectError", "consulte con su proveedor", $ex->getMessage(), "400" );

        } catch (\GuzzleHttp\Exception\ClientException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." GuzzleHttp\Exception\ClientException");
            $response = json_decode($ex->getResponse()->getBody()->getContents());
            Log::debug(print_r( $response,true));
            
            return $this->sendError("LTD ClientException",$response, "400");

        } catch (\GuzzleHttp\Exception\InvalidArgumentException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__.__LINE__." InvalidArgumentException");
            Log::debug(print_r($ex->getMessage(),true));
            return $this->sendError("InvalidArgumentException",$ex->getMessage(), "400");

        } catch (\GuzzleHttp\Exception\ServerException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__.__LINE__." ServerException");
            $response = $ex->getResponse()->getBody()->getContents();
            Log::debug(print_r($response,true));
            Log::debug(print_r(json_decode($response),true));
            return $this->sendError("ServerException",$ex->getMessage(), "400");            

        } catch (\ErrorException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." ErrorException");
            Log::debug(print_r($ex,true));
            
            $mensaje =$ex->getMessage();
            return $this->sendError("ErrorException",$ex->getMessage(), "400");

        } catch (\HttpException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." HttpException");
            $resultado = $ex;
            return $this->sendError("HttpException ",$ex->getMessage(), "400");
        } catch (\Exception $e) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." Exception");
            Log::debug($e->getMessage());
            return $this->sendError("Exception","Favor de validar con tu proveedor", "400");
        }
    }//fin function
    

    /**
     * Se busca obtener las frecuncias de coberturra oara stafeta .
     * 
     * @author Javier Hernandez
     * @copyright 2022-2025 XpertaMexico
     * @package App\Http\Controllers\API\Ltd\EstafetaController
     * @api
     * @ajax
     * 
     * @version 1.0.0
     * 
     * @since 1.0.0 Primera version de la funcion frecuencia
     * 
     * @throws ValidationException
     *
     * @param  Illuminate\Http\Request  $request Recibe la peticion del cliente
     * 
     * @var array $data Se convierte el Json de la peticion a array
     * @var array $request  
     * @var array $response Usado para obtener la respuesta del servcvio REST de ESTAFETA
     * 
     * 
     * @return json Objeto con la respuesta de exito o fallo 
     */

    public function frecuencia(Request $request){
        try{

            Log::info(__CLASS__."-".__FUNCTION__."-".__LINE__."-".$this->numeroSolicitud);
            $data =$request->all();

            Log::info(__CLASS__."-".__FUNCTION__."-".__LINE__."-".json_encode($data));    

             if ( !isset($data['token']) ) {
                Log::info(__CLASS__."-".__FUNCTION__."-".__LINE__."-$this->numeroSolicitud-");
                $empresaId = auth()->user()->empresa_id;
                $data['empresa_id']= $empresaId;
            }

            $data['numero_solicitud'] = $this->numeroSolicitud;
            $data['ltd_id'] = 2;
            $data['esManual']="API";
            
            Log::info(__CLASS__."-".__FUNCTION__."-".__LINE__."-$this->numeroSolicitud-".count($data));  
            
            
            $data['cp_origen'] = $request->route()->parameter('cp_origen');
            $data['cp_destino'] =$request->route()->parameter('cp_destino');
            
            Log::info(__CLASS__."-".__FUNCTION__."-".__LINE__."-$this->numeroSolicitud");
            $nEstafetaCreacion = new nEstafetaCreacion($data['numero_solicitud']);
            $nEstafetaCreacion->frecuencia($data);
            

            return $this->successResponse( $nEstafetaCreacion->getResponse(), $nEstafetaCreacion->getNotices());


        } catch (ValidationException $ex) {
            Log::info(__CLASS__."-".__FUNCTION__."-".__LINE__."-$this->numeroSolicitud");
            $mensaje = $ex->getMessage();
            Log::info(__CLASS__."-".__FUNCTION__."-".__LINE__."-$this->numeroSolicitud-$mensaje");
            
            return $this->sendError("ValidationException",$mensaje, "400");
        
        } catch (ModelNotFoundException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__.__LINE__." ModelNotFoundException");
            Log::debug(print_r($ex->getMessage(),true));
            return $this->sendError("ModelNotFoundException","Favor de contactar al proveedor", "400");
            
        } catch (\Spatie\DataTransferObject\DataTransferObjectError $ex) {
            Log::info(__CLASS__." ".__FUNCTION__.__LINE__." DataTransferObjectError");
            Log::debug(print_r($ex->getMessage(),true));
            return $this->sendError("DataTransferObjectError", "consulte con su proveedor", $ex->getMessage(), "400" );

        } catch (\GuzzleHttp\Exception\ClientException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." GuzzleHttp\Exception\ClientException");
            $response = $ex->getResponse()->getBody()->getContents();
            Log::debug(print_r($response,true));
            
            return $this->sendError("LTD ClientException",$response, "400");

        } catch (\GuzzleHttp\Exception\InvalidArgumentException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__.__LINE__." InvalidArgumentException");
            Log::debug(print_r($ex->getMessage(),true));
            return $this->sendError("InvalidArgumentException",$ex->getMessage(), "400");

        } catch (\GuzzleHttp\Exception\ServerException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__.__LINE__." ServerException");
            $response = $ex->getResponse()->getBody()->getContents();
            Log::debug(print_r($response,true));
            Log::debug(print_r(json_decode($response),true));
            return $this->sendError("ServerException",$ex->getMessage(), "400");

        } catch (GuzzleConnectException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." GuzzleConnectException ".$ex->getMessage() );
            Log::debug(print_r($ex,true));
            return $this->sendError("Connect Exception","No se pudo contactar al LTD, intente nuevamente", "503");                

        } catch (\ErrorException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." ErrorException");
            Log::debug(print_r($ex,true));
            
            $mensaje =$ex->getMessage();
            return $this->sendError("ErrorException",$ex->getMessage(), "400");

        } catch (\HttpException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." HttpException");
            $resultado = $ex;
            return $this->sendError("HttpException ",$ex->getMessage(), "400");
        } catch (\Exception $e) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." Exception");
            Log::debug($e->getMessage());
            Log::debug($e);
            return $this->sendError("Exception","Favor de validar con tu proveedor", "400");
        }
    }//fin function
}
?>