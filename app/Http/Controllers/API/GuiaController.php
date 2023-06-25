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
use DB;
use Illuminate\Support\Facades\Storage;

#CLASES DE NEGOCIO 
use App\Singlenton\Estafeta ; //PRODUCTION
use App\Singlenton\Fedex as sFedex ; //PRODUCTION
use App\Dto\Guia as GuiaDTO;
use App\Models\Guia;
use App\Models\API\Guia as GuiaAPI;
use App\Models\API\Rastreo_peticion;
use App\Models\EmpresaEmpresas;
use App\Models\GuiasPaquete;

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
            $sEstafeta = new Estafeta( $data['empresa_id'], "API", 1);

            Log::debug(__CLASS__." ".__FUNCTION__." sEstafeta -> envio()");
            Log::debug( json_encode($data) );

            $sEstafeta -> envio((object)$data, "API");
            $resultado = $sEstafeta->getResultado();
            Log::debug(__CLASS__." ".__FUNCTION__." ".__LINE__);
            Log::debug(print_r($resultado,true));

            $trackingNumbers = explode("|", $sEstafeta->getTrackingNumber());
            Log::debug(print_r($trackingNumbers ,true)); 

            $carbon = Carbon::parse();
            $unique = crypt( (string)$carbon,'st');
            $carbon->settings(['toStringFormat' => 'Y-m-d-H-i-s']);
            $namePdf = sprintf("%s-%s.pdf",(string)$carbon,$unique);
            Storage::disk('public')->put($namePdf,base64_decode($sEstafeta->documento));

            $insert = GuiaDTO::estafeta($sEstafeta, $request);

            $notices = array();
            $boolPrecio = true;
            $ids = "";
            foreach ($trackingNumbers as $key => $trackingNumber) {
                Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
                $insert['tracking_number'] = $trackingNumber;
                $insert['documento'] = $namePdf;
                Log::debug(print_r($insert ,true));   
                //dd("prueba");
                $id = Guia::create($insert)->id;
                $ids = sprintf("%s,%s",$ids, $ids);
                $notices[] = sprintf("El registro de la solicitud se genero con exito con el ID %s ", $id);

                Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
                $plataforma = "API";
                $guiaPaqueteInsert = GuiaDTO::validaPiezasPaquete($request, $key, $boolPrecio, $id, $plataforma);
                $boolPrecio = false;

                $idGuiaPaquite = GuiasPaquete::create($guiaPaqueteInsert)->id;
            }
            $mensaje = array("La guia se creo con exito","Guia con IDs $ids");
            /*
            $id = Guia::create($insert)->id;
            $mensaje = array("La guia se creo con exito","Guia con ID $id");
            Log::info(__CLASS__." ".__FUNCTION__." FIN");
            */
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
    public function guiasTabla(){
        Log::info(__CLASS__." ".__FUNCTION__." INICIANDO-----------------");
        $codeHttp = 404;
        try {
            
                $tabla = Guia::select('guias.*','sucursals.cp', 'sucursals.ciudad','sucursals.contacto', 'clientes.cp as cp_d', 'clientes.ciudad as ciudad_d', 'clientes.contacto as contacto_d','empresas.nombre', 'rastreo_estatus.nombre as rastreo_nombre', 'ltds.nombre as mensajeria', 'servicios.nombre as servicio_nombre',DB::raw('DATE_FORMAT(    guias.created_at, "%Y-%c-%d %H:%i") as creada')
                    ,DB::raw('DATE_FORMAT(guias.ultima_fecha, "%Y-%c-%d") as ultima_fecha_f')
                    ,DB::raw('DATE_FORMAT(guias.pickup_fecha, "%Y-%c-%d") as pickup_fecha_f')
                    , 'tiempo_entrega'
                    , 'guias_paquetes.peso as peso_u'
                    , 'guias_paquetes.alto as alto_u'
                    , 'guias_paquetes.largo as largo_u'
                    , 'guias_paquetes.ancho as ancho_u'
                )
                
                ->join('sucursals', 'sucursals.id', '=', 'guias.cia')
                ->join('clientes', 'clientes.id', '=', 'guias.cia_d')
                ->join('empresas', 'empresas.id', '=', 'sucursals.empresa_id')
                ->join('rastreo_estatus', 'rastreo_estatus.id', '=', 'guias.rastreo_estatus')
                ->join('ltds', 'ltds.id', '=', 'guias.ltd_id')
                ->join('servicios','servicios.id', '=', 'guias.servicio_id')
                ->leftJoin('guias_paquetes', 'guias_paquetes.guia_id', '=', 'guias.id' )
                //->offset(0)->limit(10)
                //->where('guias.ltd_id',1)
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
            Log::debug(print_r($ex,true));
            $this->error = "Exception";
            $this->mensaje =$ex->getMessage();
        }

        return $this->sendError("Exception",$this->mensaje, $codeHttp);
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

            $this->rastreoFedex();
            $this->rastreoEstafeta();

            
            Log::debug(print_r(Carbon::now()->toDateTimeString(),true));
            
            Rastreo_peticion::where('id',$rastreoPeticionesID)
                ->update(array("peticion_fin"=>Carbon::now()->toDateTimeString() 
                        ,"completado"=>true
                        ,"usuario"=> auth()->user()->name
                        ) 
                    );
            
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
    }//fin reastreo, Global para actualizar el esatus de las guias


    /**
     * Funcion para actualizar los registros para la tabla de ratreo 
     * 
     * @param 
     * @var 
     * 
     * @return \Illuminate\Http\Response
     */
    public function rastreoActualizarAutomatico(){
        Log::info(__CLASS__." ".__FUNCTION__." INICIANDO-----------------");
        $codeHttp = 404;
        try {

            $rastreoPeticionesID = Rastreo_peticion::create( array("ltd_id"=>Config('ltd.estafeta.id')) )->id;

            $this->rastreoEstafeta(true);
            
            Log::debug(print_r(Carbon::now()->toDateTimeString(),true));
            
            Rastreo_peticion::where('id',$rastreoPeticionesID)
                ->update(array("peticion_fin"=>Carbon::now()->toDateTimeString() 
                        ,"completado"=>true) 
                    );
            
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

        return $this->sendError($this->error,$this->mensaje, $codeHttp);
    }//fin reastreo, Global para actualizar el esatus de las guias



    /**
     * Funcion para actualizar los registros para la tabla de ratreo 
     * 
     * @param 
     * @var 
     * 
     * @return \Illuminate\Http\Response
     */
    public function rastreoAutomaticoFedex(){
        Log::info(__CLASS__." ".__FUNCTION__." INICIANDO-----------------");
        $codeHttp = 404;
        try {

            $rastreoPeticionesID = Rastreo_peticion::create( array("ltd_id"=>Config('ltd.fedex.id')) )->id;

            $this->rastreoFedex(true);
            
            
            Log::debug(print_r(Carbon::now()->toDateTimeString(),true));
            
            Rastreo_peticion::where('id',$rastreoPeticionesID)
                ->update(array("peticion_fin"=>Carbon::now()->toDateTimeString() 
                        ,"completado"=>true) 
                    );
            
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

        return $this->sendError($this->error,$this->mensaje, $codeHttp);
    }//fin reastreo, Global para actualizar el esatus de las guias

    

    /**
     * Funcion para actualizar guias de Fedex ltd = 1 
     * 
     * @param 
     * @var 
     * 
     * @return \Illuminate\Http\Response
     */
    private function rastreoFedex(bool $automatico = false){
        Log::info(__CLASS__." ".__FUNCTION__." INICIANDO-----------------");
        $guia = array();
        $sFedex = sFedex::getInstance(1,1,"ATM");
        if ($automatico){
            $guias = $this->consultaGuiaParaRastreoAutomatico(Config('ltd.fedex.id'));
        }else{
            $guias = $this->consultaGuiaParaRastreo(Config('ltd.fedex.id'));    
        }
        
        $guiaCantidad = count($guias);
        $i = 0;
        foreach ($guias as $key => $value) {
            Log::info("-----".++$i."/$guiaCantidad -----");
            Log::debug($value);

            $sFedex->rastreo($value['tracking_number']);
            $update = array();
            if ($sFedex->getExiteSeguimiento()) {   
                Log::info(__CLASS__." ".__FUNCTION__." Valida seguimiento");
                $scanEvents = $sFedex->getScanEvents();
                $latestStatusDetail = $sFedex->getLatestStatusDetail();
                $paquete = $sFedex->getPaquete();
                $quienRecibio = $sFedex->getQuienRecibio();    
                $ultimaFecha = Carbon::parse($scanEvents->date)->format('Y-m-d H:i:s');

                $update = array('ultima_fecha' => $ultimaFecha
                        ,'rastreo_estatus' => Config('ltd.fedex.rastreoEstatus')[$latestStatusDetail->derivedCode]
                        ,'rastreo_peso' => $paquete['peso'] 
                        ,'largo' => $paquete['largo'] 
                        ,'ancho' => $paquete['ancho'] 
                        ,'alto' => $paquete['alto']
                        ,'quien_recibio' =>  $quienRecibio
                        ,'pickup_fecha' =>  $sFedex->getPickupFecha()

                    );

                
                Log::debug(print_r($update,true));
                Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." Actualizado Guia");
                $affectedRows = GuiaAPI::where("id", $value['id'])
                        ->update($update);
                
                Log::debug("affectedRows -> $affectedRows");
            }
        } // fin foreach ($tabla as $key => $value)
        Log::info(__CLASS__." ".__FUNCTION__." FINALIZANDO-----------------");
    }// Fin rastreoFedex


    /**
     * Funcion para actualizar guias de Estafeta ltd = 2 
     * 
     * @param bool $automatico, Indica si la peticion es via crontab es igual a true
     * @var 
     * 
     * @return \Illuminate\Http\Response
     */
    private function rastreoEstafeta(bool $automatico = false){
        Log::info(__CLASS__." ".__FUNCTION__." INICIANDO-----------------");
        $guia = array();
        $plataforma = "AUTOMATICO";
        $servicioID = 2;
        
        if ($automatico){
            $empresaId = 2;
        }else{
            $empresaId = auth()->user()->empresa_id;    
        }
        Log::info(__CLASS__." ".__FUNCTION__." empresaId $empresaId");

        $guias = $this->consultaGuiaParaRastreoAutomatico( Config('ltd.estafeta.id'), $empresaId);
        $sEstafeta = Estafeta::getInstance($empresaId,$plataforma, $servicioID);

        $guiaCantidad = count($guias);
        $i = 0;
        foreach ($guias as $key => $value) {
            Log::info("-----".++$i."/$guiaCantidad -----");
            Log::debug($value);

            $sEstafeta->rastreo($value['tracking_number']);
            $update = array();
            
            if ($sEstafeta->getExiteSeguimiento()) {   
                Log::info(__CLASS__." ".__FUNCTION__." Valida seguimiento");
                $paquete = $sEstafeta->getPaquete();

                $update = array('ultima_fecha' => $sEstafeta->getUltimaFecha()
                        ,'rastreo_estatus' => Config('ltd.estafeta.rastreoEstatus')[$sEstafeta->getLatestStatusDetail()]
                        ,'rastreo_peso' => $paquete['peso'] 
                        ,'largo' => $paquete['largo'] 
                        ,'ancho' => $paquete['ancho'] 
                        ,'alto' => $paquete['alto']
                        ,'quien_recibio' =>  $sEstafeta->getQuienRecibio()
                        ,'pickup_fecha' =>  $sEstafeta->getPickupFecha()

                    );

                Log::info(print_r($update,true));

                $affectedRows = GuiaAPI::where("id", $value['id'])
                        ->update($update);
    
                Log::debug("affectedRows -> $affectedRows");
            }else{
                Log::info(__CLASS__." ".__FUNCTION__." Sin seguimiento");
            }
            
        } // fin foreach ($tabla as $key => $value)
        Log::info(__CLASS__." ".__FUNCTION__." FINALIZANDO-----------------");
    }// Fin rastreoFedex



    /**
     * Funcion para consultar guias con estatus [creada, recolectada, transito ] 
     * 
     * @param 
     * @var 
     * 
     * @return \Illuminate\Http\Response
     */
    private function consultaGuiaParaRastreo(int $ltdId ){
        Log::info(__CLASS__." ".__FUNCTION__." INICIANDO-----------------");
        $guias = Guia::select('id','ltd_id', 'tracking_number')
                    ->where('ltd_id',$ltdId)
                    ->whereIN('rastreo_estatus',array(1,2,3))
                    //->offset(0)->limit(20)
                    ->get()->toArray();
        Log::info("Total de guias revisar ".count($guias));
        Log::info(__CLASS__." ".__FUNCTION__." FINALIZANDO-----------------");
        return $guias;
    }

    /**
     * Funcion para consultar guias con estatus [creada, recolectada, transito ], 
     * basado en el flujo automatico 
     * 
     * @param 
     * @var 
     * 
     * @return \Illuminate\Http\Response
     */
    private function consultaGuiaParaRastreoAutomatico(int $ltdId, int $empresaId = 1 ){
        Log::info(__CLASS__." ".__FUNCTION__." INICIANDO-----------------");
        
        $guias = GuiaAPI::select('id','ltd_id', 'tracking_number')
                    ->where('ltd_id',$ltdId)            
                    ->whereIN('rastreo_estatus',array(1,2,3))
                    //->offset(0)->limit(10)
                    ->orderBy('id', 'DESC')
                    ->get()->toArray();
        Log::info("Total de guias revisar ".count($guias));
        Log::info(__CLASS__." ".__FUNCTION__." FINALIZANDO-----------------");
        return $guias;
    }
}
