<?php
namespace App\Dto\Empresas;

use Log;


class EmpresaDTO 
{
    
    function __construct()
    {
        // code...
    }

    public function parser($data){
    	Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
    	$contacto = sprintf("%s %s %s",$data['name'],$data['apellido_paterno'],$data['apellido_materno']);

    	$dataParseado['contacto']= $contacto;
    	$dataParseado['nombre']= $contacto;
    	$dataParseado['email']= $data['email'];
    	//$dataParseado['telefono']= $data[''];
    	$dataParseado['rfc']= $data['rfc'];
    	


    	Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
    	return $dataParseado;
    }


}