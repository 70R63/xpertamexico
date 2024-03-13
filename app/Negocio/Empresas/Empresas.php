<?php
namespace App\Negocio\Empresas;

//GENERAL
use Log;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

//MODELS
use App\Models\API\Empresa as mEmpresa;
use App\Models\EmpresaEmpresas as mEmpresaEmpresas;
use App\Models\Saldos\Saldos as mSaldos;

//DTOS
use App\Dto\Empresas\EmpresaDTO;

// singlenton

//Negocio


class Empresas {

    private $empresa_id = 0;


    /**
     * Se gnerar una capa de negocio para insertar la empresa despues del registro
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
     * @throws \LogicException
     *
     * @param array $parametros 
     * 
     * @var int 
     * @var App\Negocio\Fedex_tarifas $fedexTarifa
     * @var string $cp 
     * @var string $cp_d
     * @var array $body valores unicos par envio al LTD
     * @var string $canal valor que indentifica de donde se realiza la peticion
     * 
     * 
     * @return json Objeto con la respuesta de exito o fallo 
     */

    public function crear($data){
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);

        $EmpresaDTO = new EmpresaDTO();
        $dataParseado = $EmpresaDTO->parser($data);

        $empresa = mEmpresa::create($dataParseado);
        
        mEmpresaEmpresas::create(array('id' => $empresa->id
                ,'empresa_id' => $empresa->id ));

        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        $tmp = sprintf(" El registro fue exitoso");

        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        mSaldos::create(array("empresa_id" => $empresa->id));

        $this->empresa_id = $empresa->id;

    }



    public function getEmpresaId(){
        return $this->empresa_id;

    }
}