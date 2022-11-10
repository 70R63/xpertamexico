<?php
namespace App\Dto;

use Log;

use App\Dto\Estafeta\API\V3\Label;
use App\Dto\Estafeta\API\V3\Identification;
use App\Dto\Estafeta\API\V3\SystemInformation;
use App\Dto\Estafeta\API\V3\LabelDefinition;

/**
 * 
 */
class EstafetaDTO 
{
    public $data = null;
	
	function __construct()
	{
		// code...
	}

	function init( ){

		Log::info(__CLASS__." ".__FUNCTION__); 

        
		$Dralternativeinfo = new DrAlternativeInfo();  

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

     /**
     * Metodo para asignar los valores del request a una etiqueta 
     * para generar el venvio a la api de Fedex
     * 
     * 
     * @param \Illuminate\Http\Request $request
     * @return App\Dto\Fedex\Etiqueta Etiqueta
     */

    public function parser(array $data){
        Log::debug(__CLASS__." ".__FUNCTION__." INICIO");

        $identification = new Identification();
        $systemInformation = new SystemInformation();

        Log::debug(print_r($data,true));
       
        $body = new Label(
            [
                "identification" => $identification
                ,"systemInformation" => $systemInformation
                ,"labelDefinition"  => $data['labelDefinition']
            ]
        );

        Log::debug("Label armada--------------------------");
        Log::debug(print_r($body,true));

        Log::debug(__CLASS__." ".__FUNCTION__." FIN");
        return $body;
    }//fin public function parser


    private function parserForma($data){

        dd($data);
        $this->data;
    }

}

?>
