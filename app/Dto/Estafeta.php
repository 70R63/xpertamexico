<?php
namespace App\Dto;

use Log;

use App\Dto\Estafeta\Label;
use App\Dto\Estafeta\LabelDescriptionList;
use App\Dto\Estafeta\OriginInfo;
use App\Dto\Estafeta\DestinationInfo;
use App\Dto\Estafeta\DrAlternativeInfo;

use Spatie\DataTransferObject\DataTransferObjectError;

/**
 * 
 */
class Estafeta 
{
    public $data = null;
	
	function __construct()
	{
		// code...
	}

	function init( ){

		Log::info(__CLASS__." ".__FUNCTION__); 

        
		//$originInfo = new OriginInfo();
		//$destinationInfo = new DestinationInfo();
        /*No se porque debo de inicializar esta clase*/
		$Dralternativeinfo = new DrAlternativeInfo();  

        /*
        son los datos de cada cliente
            $data['suscriberId'] = $this->mensajeria->suscriberId;
            $data['customerNumber'] = $this->mensajeria->customerNumber ;
            $data['password'] = $this->mensajeria->ws_pass ;
            $data['login'] = $this->mensajeria->login ;
        */
        try{

            /* Se inicializa el WS para DEV*/
            $wsdl = config('ltd.estafeta');
            $path_to_wsdl = sprintf("%s%s",resource_path(), $wsdl );
            Log::debug($path_to_wsdl);

            Log::debug($this->data);
            $labelDTO = new Label($this->data);
			Log::debug(serialize($labelDTO));

            $client = new \SoapClient($path_to_wsdl, array('trace' => 1));
            ini_set("soap.wsdl_cache_enabled", "0");
            $response =$client->createLabel($labelDTO);

            Log::debug(__CLASS__." ".__FUNCTION__." ");
            Log::debug(print_r($response->globalResult,true));
            Log::debug(print_r($response->labelResultList,true));
            Log::info(__CLASS__." ".__FUNCTION__." Fin Try");
            return response()->json([
                'codigo' => $response->globalResult->resultCode,
                'descripcion' => $response->globalResult->resultDescription
                ,'pdf'  => base64_encode($response->labelPDF)
            ]);
                 
        
        } catch (DataTransferObjectError $exception) {
            Log::info(__CLASS__." ".__FUNCTION__." DataTransferObjectError "); 
            
            return response()->json([
                'codigo' => "11",
                'descripcion' => $exception->getMessage()
                ,'pdf'  => null
                ]);

        } catch(Exeption $e){
            Log::info(__CLASS__." ".__FUNCTION__."Exeption ");
        	return response()->json([
                'codigo' => "11",
                'descripcion' => $e->getMessage()
                ,'pdf'  => null
                ]);
        }


	}

    public function parser($data,$tipo = "FORMA"){
        if ("API" === $tipo) {
            $this->data = $data->all();
        } else {
            $this->parserForma($data);
        }
    }//fin public function parser


    private function parserForma($data){

        dd($data);
        $this->data;
    }

}

?>
