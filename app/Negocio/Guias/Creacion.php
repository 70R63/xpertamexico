<?php

namespace App\Negocio\Guias;

use Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

//MODELS
use App\Models\Guia;
use App\Models\GuiasPaquete;

//DTOS
use App\Dto\FedexDTO;
use App\Dto\Guia as GuiaDTO;

// singlenton
use App\Singlenton\Fedex as sFedex;

class Creacion {


	private $insert = array();
	private $notices = array();
	private $namePdf = array();
    private $response;

	/**
     * Se obtienen los datos para armar el insert de fedex
     * 
     * @param array $parametros
     * @return void
     */

    public function fedex($data, $canal="API" ){
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        $fedexDTO = new FedexDTO();
        $etiqueta = $fedexDTO->parser($data);

        $this->fedex = sFedex::getInstance(Config('ltd.fedex.id'));
        $this->fedex->envio( json_encode($etiqueta, JSON_UNESCAPED_UNICODE));

        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        $guiaDTO = new GuiaDTO();
        $guiaDTO->parseoFedex($data, $canal);
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        $this->insert = $guiaDTO->getInsert();
    }

    /**
     * Se obtienen los datos para armar el insert de fedex
     * 
     * @param array $parametros
     * @return void
     */
    public function recurenciaPorDocumento($data, $numeroDeSolicitud, $canal="API"){
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        $boolPrecio = true;
        $i=1;
        
        $this->notices = array("Número de Solicitud: $numeroDeSolicitud ");
        foreach ($this->fedex->getDocumentos() as $key => $documento) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
            
            $this->insert['tracking_number'] = $documento->trackingNumber;
            $this->insert['documento'] = $documento->packageDocuments[0]->url;
            $this->insert['numero_solicitud'] = $numeroDeSolicitud;
            Log::debug(print_r($this->insert ,true));

            $carbon = Carbon::now();
            $unique = md5( (string)$carbon);
            $carbon->settings(['toStringFormat' => 'Y-m-d-H-i-s.u']);
            $this->namePdf = sprintf("%s-%s-%s.pdf",(string)$carbon,$this->insert['empresa_id'],$unique);

            Storage::disk('public')->put($this->namePdf, file_get_contents($this->insert['documento']));


            if ($i > 1) {
                Log::debug(__CLASS__." ".__FUNCTION__." ".__LINE__." Limpiar costos");
                $this->insert = nGuia::costosEnCero( $this->insert );
            }   
            
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." Guia::create");
            $this->insert['canal'] = $canal;
            $id = Guia::create($this->insert)->id;
            $this->notices[] = sprintf("El registro de la solicitud se genero con exito con el ID %s ", $id);


            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
            $guiaPaqueteInsert = GuiaDTO::validaPiezasPaquete($data, $key, $boolPrecio, $id);
            $boolPrecio = false;

            $idGuiaPaquite = GuiasPaquete::create($guiaPaqueteInsert)->id;
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." idGuiaPaquite =$idGuiaPaquite");
            $i++;
        }

    }

    /**
     * Se obtienen los datos para armar el insert de fedex
     * 
     * @author Javier Hernandez
     * @copyright 2022-2023 XpertaMexico
     * @package App\Negocio\Guias
     * @api
     * 
     * @version 1.0.0
     * 
     * @since 1.0.0 Primera version de la funcion fedexApi
     * 
     * @throws
     *
     * @param array $parametros eseseses
     * 
     * @var int 
     * 
     * 
     * @return json Objeto con la respuesta de exito o fallo 
     */

    public function fedexApi($data){
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
            

        $data['accountNumber']['value'] = config('ltd.fedex.cred.accountNumber');

        $labelSpecification = ['imageType' => 'PDF'
                            , 'labelStockType'=> 'PAPER_85X11_TOP_HALF_LABEL'
                            ];
        $data['requestedShipment']['labelSpecification'] = $labelSpecification;

        $data['requestedShipment']['packagingType'] = 'YOUR_PACKAGING';
        $data['requestedShipment']['pickupType'] = 'USE_SCHEDULED_PICKUP';

        $data['requestedShipment']['blockInsightVisibility'] = false;

        $paymentType = ['paymentType' => 'SENDER'];
        $data['requestedShipment']['shippingChargesPayment'] = $paymentType;

        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        $this->fedex = sFedex::getInstance(config('ltd.fedex.id'), 2, "API");
        $this->fedex->envio( json_encode($data, JSON_UNESCAPED_UNICODE));
       

        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
    }

    public function getNotices(){
        return $this->notices;
    }

    public function getNamePdf(){
        return $this->namePdf;
    }

	


}