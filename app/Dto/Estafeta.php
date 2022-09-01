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
	
	function __construct()
	{
		// code...
	}

	function init( $request ){

		Log::info(__CLASS__." ".__FUNCTION__); 

        
		//$originInfo = new OriginInfo();
		//$destinationInfo = new DestinationInfo();
        /*No se porque debo de inicializar esta clase*/
		$Dralternativeinfo = new DrAlternativeInfo();  

        $data = $request->all();
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

            Log::debug($data);
            $labelDTO = new Label($data);
			 
            $client = new \SoapClient($path_to_wsdl, array('trace' => 1));
            ini_set("soap.wsdl_cache_enabled", "0");
            $response =$client->createLabel($labelDTO);

            Log::info(__CLASS__." ".__FUNCTION__." ".$response->globalResult->resultDescription);
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
}

?>