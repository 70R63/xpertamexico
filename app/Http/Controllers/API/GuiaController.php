<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\ApiController as Controller;
use Illuminate\Http\Request;
use Log;
use Laravel\Sanctum\HasApiTokens;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

#CLASES DE NEGOCIO 
use App\Singlenton\Estafeta ; //PRODUCTION
use App\Singlenton\Fedex as sFedex ; //PRODUCTION
use App\Dto\Guia as GuiaDTO;
use App\Models\Guia;
use App\Models\Rastreo_peticion;

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

    private $codeHttp = 500;
    private $error = "Error general";
    private $mensaje = array("Error inesperado consulte con su proveedor");
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

        
            $body = "grant_type=client_credentials&client_id=l7640a59a8ce1c4dfea7bb2d302febc882&client_secret=2bc10d1d2f3b4b6ab55a0e63518c306e";


            $response = $client->request('POST', 'oauth/token', [
                'headers'   => $headers
                ,'body'     => $body
                
            ]);

            Log::debug(print_r($response>getBody()->scope,true));
            Log::debug("Fin Response --------------------");
            $resultado = "RESPONSE";
            $mensaje = "LA guia se creo con exito";
            return $this->successResponse($resultado, $mensaje);
        
            
        } catch (\GuzzleHttp\Exception\ClientException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." ClientException");
            Log::debug(print_r($ex,true));
            
            return $this->successResponse("Response", "ClientException");
        } catch (\GuzzleHttp\Exception\InvalidArgumentException $ex) {
            Log::debug($ex);
            return $this->successResponse("Response", "InvalidArgumentException");
        } catch (HttpException $ex) {
          
            $resultado = $ex;
            $mensaje = "La guia no pudo ser creada";
            return $this->successResponse($resultado, $mensaje);
        }
    }


    /**
     * ESTAFETA CREACION DE GUIA
     * @param body,json con estructura para la guia
     * @var ltd_id Estafeta sera 2
     * 
     * @return \Illuminate\Http\Response
     */
    public function estafeta(Request $request){
        Log::info(__CLASS__." ".__FUNCTION__." INICIO");

        Log::debug($request);
        $response = null;
        $trackingNumber = "";
        $systemInformation = array("id"=>"AP01",
            "name"=>"AP01",
            "version"=>"1.10.20");

        $identification = array(
            "suscriberId"=>Config('ltd.estafeta.cred.suscriberId'),
            "customerNumber"=>Config('ltd.estafeta.cred.customerNumber')
        );

        $salesOrganization = Config('ltd.estafeta.cred.salesOrganization'); 
        Log::debug($identification);
        try {

            $data = $request->except(['api_token']);
            if(empty($data))
                 return $this->sendError("Body, sin estructura o vacio", null, "400");

            $data['systemInformation']= $systemInformation;
            $data['identification']=$identification;
            $data['labelDefinition']['serviceConfiguration']['salesOrganization']=$salesOrganization; 

            Log::debug("Se intancia el Singlento Estafeta");
            $sEstafeta = new Estafeta(Config('ltd.estafeta.id'), $data['empresa_id'], "API");

            Log::debug(__CLASS__." ".__FUNCTION__." sEstafeta -> envio()");
            Log::debug( json_encode($data) );

            $sEstafeta -> envio($data);
            $resultado = $sEstafeta->getResultado();
            $trackingNumber = $sEstafeta->getTrackingNumber();
            Log::debug(print_r($resultado,true));

            $insert = GuiaDTO::estafeta($sEstafeta, $request);
            $id = Guia::create($insert)->id;
            $mensaje = array("La guia se creo con exito","Guia con ID $id");
            Log::info(__CLASS__." ".__FUNCTION__." FIN");
            return $this->successResponse($resultado, $mensaje);
        
        } catch (\Spatie\DataTransferObject\DataTransferObjectError $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." DataTransferObjectError");
            Log::debug(print_r($ex->getMessage(),true));
            return $this->sendError("tracking :$trackingNumber, consulte con su proveedor", $ex->getMessage(), "400" );

        } catch (\GuzzleHttp\Exception\ClientException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__."GuzzleHttp\Exception\ClientException");
             $response = $ex->getResponse()->getBody()->getContents();
            Log::debug(print_r($response,true));
            
            return $this->sendError("tracking :$trackingNumber",$response, "400");

        } catch (\GuzzleHttp\Exception\InvalidArgumentException $ex) {
            Log::debug($ex );
            return $this->sendError("tracking :$trackingNumber",$ex->getMessage(), "400");

        } catch (\GuzzleHttp\Exception\ServerException $ex) {
            $response = $ex->getResponse()->getBody()->getContents();
            Log::debug(print_r($response,true));
            Log::debug(print_r(json_decode($response),true));
            return $this->successResponse(json_decode($response), "ServerException");            

        } catch (\InvalidArgumentException $ex) {
            Log::debug($ex );
            return $this->successResponse("Response", "InvalidArgumentException","400");

        } catch (\ErrorException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." ErrorException");
            Log::debug(print_r($ex,true));
            
            $mensaje =$ex->getMessage();
            return $this->sendError("tracking :$trackingNumber",$ex->getMessage(), "400");

        } catch (\HttpException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." HttpException");
            $resultado = $ex;
            $mensaje = "La guia no pudo ser creada";
            return $this->sendError("tracking :$trackingNumber",$mensaje, "400");
        } catch (\Exception $e) {
            Log::info(__CLASS__." ".__FUNCTION__." Exception");
            return $this->sendError("Exception",$e->getMessage(), "400");
        }
    }// Fin public function Estafeta


    /**
     * Funcion para regresar los registros para la tabla de ratreo 
     * 
     * @param 
     * @var 
     * 
     * @return \Illuminate\Http\Response
     */
    public function rastreoTabla(){
        Log::info(__CLASS__." ".__FUNCTION__." INICIANDO-----------------");
        try {
            
            $tabla = Guia::select('guias.*','sucursals.cp', 'sucursals.ciudad','sucursals.contacto', 'clientes.cp as cp_d', 'clientes.ciudad as ciudad_d', 'clientes.contacto as contacto_d','empresas.nombre', 'rastreo_estatus.nombre as rastreo_nombre', 'ltds.nombre as mensajeria', 'servicios.nombre as servicio_nombre')
                    ->join('sucursals', 'sucursals.id', '=', 'guias.cia')
                    ->join('clientes', 'clientes.id', '=', 'guias.cia_d')
                    ->join('empresas', 'empresas.id', '=', 'sucursals.empresa_id')
                    ->join('rastreo_estatus', 'rastreo_estatus.id', '=', 'guias.rastreo_estatus')
                    ->join('ltds', 'ltds.id', '=', 'guias.ltd_id')
                    ->join('servicios','servicios.id', '=', 'guias.servicio_id')
                    //->offset(0)->limit(10)
                    //->toSql();
                    //->where('guias.created_at', '>', now()->subDays(30)->endOfDay())
                    ->get()->toArray();
            Log::info(__CLASS__." ".__FUNCTION__." FINALIZANDO-----------------");
            return $this->successResponse($tabla, 'successfully.');
            
        } catch (\ErrorException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." ErrorException");
            Log::debug(print_r($ex,true));
            $this->error = "ErrorException";
            $this->mensaje =$ex->getMessage();
            

        } catch (\HttpException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." HttpException");
            $resultado = $ex;

            $this->error = "HttpException";
            $this->mensaje =$ex->getMessage();
        } catch (\Exception $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." Exception");
            $this->error = "Exception";
            $this->mensaje =$ex->getMessage();
        }

        return $this->sendError("Exception",$e->getMessage(), $codeHttp);
    }//fin reastreo


    /**
     * Funcion para actualizar los registros para la tabla de ratreo 
     * 
     * @param 
     * @var 
     * 
     * @return \Illuminate\Http\Response
     */
    public function rastreoActualizar(){
        Log::info(__CLASS__." ".__FUNCTION__." INICIANDO-----------------");
        try {

            $rastreoPeticionesID = Rastreo_peticion::create()->id;

            $tabla = Guia::select('id','ltd_id', 'tracking_number')
                    ->where('ltd_id',1)
                    ->whereIN('rastreo_estatus',array(1,2,3))
                    //->offset(0)->limit(10)
                    ->get()->toArray();
            Log::info("Total de guias revisar ".count($tabla));
            $sFedex = sFedex::getInstance();
            $i = 0;
            foreach ($tabla as $key => $value) {
                Log::debug("-----".++$i."-----");
                Log::debug($value);

                $sFedex->rastreo($value['tracking_number']);
                
                if ($sFedex->getExiteSeguimiento()) {
                    Log::info(__CLASS__." ".__FUNCTION__." Valida seguimiento");
                    $scanEvents = $sFedex->getScanEvents();
                    $paquete = $sFedex->getPaquete();
                    $quienRecibio = $sFedex->getQuienRecibio();    
                    $ultimaFecha = Carbon::parse($scanEvents->date)->format('Y-m-d H:i:s');

                    $update = array('ultima_fecha' => $ultimaFecha
                            ,'rastreo_estatus' => Config('ltd.fedex.rastreoEstatus')[$scanEvents->derivedStatusCode]
                            ,'rastreo_peso' => $paquete['peso'] 
                            ,'largo' => $paquete['largo'] 
                            ,'ancho' => $paquete['ancho'] 
                            ,'alto' => $paquete['alto']
                            ,'quien_recibio' =>  $quienRecibio

                        );

                    
                    Log::debug(print_r($update,true));

                    $affectedRows = Guia::where("id", $value['id'])
                            ->update($update);

                    Log::debug("affectedRows -> $affectedRows");
                }
            }
            
            Log::debug(print_r(Carbon::now()->toDateTimeString(),true));
            Rastreo_peticion::where('id',$rastreoPeticionesID)
                ->update(array("peticion_fin"=>Carbon::now()->toDateTimeString() ) );
            
            $tabla= array();
            Log::info(__CLASS__." ".__FUNCTION__." FINALIZANDO-----------------");
            return $this->successResponse($tabla, 'successfully.');
        } catch(\Illuminate\Database\QueryException $ex){ 
            Log::info(__CLASS__." ".__FUNCTION__." "."QueryException");
            Log::debug($ex->getMessage()); 
            $this->error = "ErrorException";
            $this->mensaje =$ex->getMessage();

        } catch (\ErrorException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." ErrorException");
            Log::debug(print_r($ex,true));
            $this->error = "ErrorException";
            $this->mensaje =$ex->getMessage();
            

        } catch (\HttpException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." HttpException");
            $resultado = $ex;

            $this->error = "HttpException";
            $this->mensaje =$ex->getMessage();
        } catch (\Exception $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." Exception");
            Log::debug(print_r($ex,true));
            $this->error = "Exception";
            $this->mensaje =$ex->getMessage();
        }

        return $this->sendError($this->error,$this->mensaje, $this->codeHttp);
    }//fin reastreo
}
