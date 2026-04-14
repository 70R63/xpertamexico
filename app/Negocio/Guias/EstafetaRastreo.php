<?php 

namespace App\Negocio\Guias;

use Log;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

//SINGLENTON
use App\Singlenton\Estafeta as sEstafeta;

//MODELS
use App\Models\API\Guia as GuiaAPI;

//NEGOCIO

//DTO

//TRAITS
use App\Traits\GettersSetters;

Class EstafetaRastreo {
	
	use GettersSetters;

	private $response;
    private $notices;
	
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
     * @since 1.0.0 Primera version de la funcion parseoRastreoManual
     * 
     * @throws
     *
     * @param  Illuminate\Http\Request  $request Recibe la paticion del cliente
     * 
     * @var array $data Se convierte el Json de la peticion a array
     * 
     * @return json Objeto con la respuesta de exito o fallo 
     */

    public function parseoRastreoManual(array $data){
    	Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);

    	$guias = $this->consultaGuiaManual($data);
        $guiaCantidad = count($guias);
        Log::info($guias);
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        if($guiaCantidad != 1)
            throw ValidationException::withMessages(array("Incongruencia de tracking o Guia no creada, Consulte a su Administrador"));
        $guia = $guias[0];

        $sEstafeta = sEstafeta::getInstance(2,"API",2);
        $sEstafeta->rastreo($guia['tracking_number']);
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

            $affectedRows = GuiaAPI::where("id", $guia['id'])
                    ->update($update);

            Log::debug("affectedRows -> $affectedRows");
            $this->notices[]= "seguimiento";
            $this->response=$sEstafeta->getResultado();
        }else{
            Log::info(__CLASS__." ".__FUNCTION__." Sin seguimiento");
            $this->notices[]= "La guia aun no cuenta seguimiento";
            $this->response=array();
        }
        
		Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
    }//public function parseoApi

    /**
     * Se busca obtener la guia basada em el tracking  .
     * 
     * @author Javier Hernandez
     * @copyright 2022-2023 XpertaMexico
     * @package App\Negocio\Guias
     * @api
     * 
     * @version 1.0.0
     * 
     * @since 1.0.0 Primera version de la funcion consultaGuiaManual
     * 
     * @throws
     *
     * @param  Illuminate\Http\Request  $request Recibe la paticion del cliente
     * 
     * @var array $data Se convierte el Json de la peticion a array
     * 
     * @return array $guias Respuesta de la consulta con ltd_id y tracking
     */

    private function consultaGuiaManual(array $data){
    	Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
    	Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." Tracking ");
    	$guias = GuiaAPI::select('id','ltd_id', 'tracking_number')
    				->where('ltd_id',$data['ltd_id'])  
    				->where('tracking_number',$data['tracking_number'])  
    				->get()->toArray()
    				;
  		return $guias;
    	Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);

    }

    /**
     * API de peticon de rastreo para Estafeta Version 2
     * Uso de API REST cambio aplicado el 202604 
     * 
     * @author Javier Hernandez
     * @copyright 2022-2026 XpertaMexico
     * @package App\Singlenton
     * 
     * @version 2.0.0
     * 
     * @since 1.0.0 Primera version de la funcion rastreo
     * 
     * @throws
     *
     * @param array $data Informacion general de la peticion
     * 
     * @var array $
     * 
     * 
     * @return void, se usara getter para los detos que se requiera
     */
    public function rastreoEstafetav2(bool $automatico = false, $paridad=2, $numeroDeSolicitud=1){
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." $numeroDeSolicitud  INICIANDO-----------------" );
        
        $guia = array();
        $plataforma = "AUTOMATICO";
        $servicioID = 2;
        
        if ($automatico){
            $empresaId = 2;
        }else{
            $empresaId = auth()->user()->empresa_id;    
        }
        Log::info(__CLASS__." ".__FUNCTION__." empresaId $empresaId");

        $guias = $this->consultaGuiaRastreoV2( Config('ltd.estafeta.id'), $empresaId,$paridad);
        $sEstafeta = sEstafeta::getInstance($empresaId,$plataforma, $servicioID);

        $guiaCantidad = count($guias);
        $i = 0;
        foreach ($guias as $key => $value) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." $numeroDeSolicitud ----------".++$i."/$guiaCantidad ----------");
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." $numeroDeSolicitud valores de guia ".print_r($value,true));
            $guia_id = $value['id'];
            try{
                $sEstafeta->rastreoV2($value['tracking_number'], $numeroDeSolicitud);
                $update = array();
                $updateSaldo = array();
                if ($sEstafeta->getExiteSeguimiento()) {   
                    Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." $numeroDeSolicitud Valida seguimiento");

                    Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." $numeroDeSolicitud Obtener Valores Singlento");
                    $paquete = $sEstafeta->getPaquete();
                    $ultimaFecha =  $sEstafeta->getUltimaFecha();
                    $rastreoEstatus = Config('ltd.estafeta.rastreoEstatus')[$sEstafeta->getLatestStatusDetail()];
                    $quienRecibio = $sEstafeta->getQuienRecibio();
                    $pickupFecha = $sEstafeta->getPickupFecha();
                    

                    Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." $numeroDeSolicitud- Calular Repesaje");
                    $nRepesaje = new nRepesaje();
                    $nRepesaje->calcularPrecio($guia_id, $paquete, $value);
                    $precioRastreo = $nRepesaje->getPrecioRastreo();
                    $esRepesaje = $nRepesaje->getEsRepesaje();


                    Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." $numeroDeSolicitud- armando update");
                    $nRastreo = new nRastreo();
                    $nRastreo->parseoUpdate($ultimaFecha, $rastreoEstatus,$quienRecibio,$pickupFecha , $precioRastreo, $paquete, $esRepesaje);
                    $update = $nRastreo->getUpdate();
                    Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ".print_r($update,true));

                    $affectedRows = GuiaAPI::where("id", $guia_id)
                            ->update($update);

                    Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." $numeroDeSolicitud- guiaId =$guia_id,  affectedRows -> $affectedRows");
                    

                    if ($esRepesaje==='SI') {
                        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." $numeroDeSolicitud- Actualizando Saldo");
                        $update['importe'] = -($precioRastreo-$value['precio']);
                        $nSaldos = new nSaldos();
                        $nSaldos->calcular($update);

                    }
                    

                }else{
                    Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." Sin seguimiento");
                }
            }  catch (\Exception $ex) {
                Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." Exception :". $ex->getMessage());
                Log::debug(print_r($ex,true));
                
            }
            
            
        } // fin foreach ($tabla as $key => $value)
        
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." FINALIZANDO-----------------");
    }// Fin rastreoEstafetav2


    /**
     * AFuncion para consultar guias con estatus [creada, recolectada, transito ], 
     * basado en el flujo automatico
     * 
     * @author Javier Hernandez
     * @copyright 2022-2026 XpertaMexico
     * @package App\Singlenton
     * 
     * @version 2.0.0
     * 
     * @since 1.0.0 Primera version de la funcion rastreo
     * 
     * @throws
     *
     * @param array $data Informacion general de la peticion
     * 
     * @var array $
     * 
     * 
     * @return void, se usara getter para los detos que se requiera
     */

    private function consultaGuiaRastreoV2(int $ltdId, int $empresaId = 1, $paridad=2 ){
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." INICIANDO-----------------");


        $guias = GuiaAPI::select('id','ltd_id', 'tracking_number', 'precio','empresa_id', 'peso', 'tarifa_id',  'servicio_id')
            ->where('ltd_id',$ltdId)            
		    ->whereNotIn('rastreo_estatus',array(4,7))
		    ->whereNotIn('empresa_id',array(307))
		    ->where('created_at', '>', now()->subDays(90)->endOfDay())
		    ->where('created_at', '<', now()->subDays(2)->endOfDay())
            //->offset(0)->limit(100)
            ->orderBy('id', 'DESC')
            //->whereIn("guias.id", array(88141,88423) )
                    
                    ;

        if ($paridad != 2) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." paridad=$paridad");
            $guias->whereRaw(" mod(id,2) = $paridad");
        }

        $guias = $guias->get()->toArray();

        Log::info("Total de guias revisar ".count($guias));
        Log::info(__CLASS__." ".__FUNCTION__." FINALIZANDO-----------------");
        return $guias;
    }
}