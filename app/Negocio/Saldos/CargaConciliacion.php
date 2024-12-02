<?php
namespace App\Negocio\Saldos;

use Log;

//modelos
use App\Models\Ltd;

//Negocio

//Utilerias
use Carbon\Carbon;

class CargaConciliacion 
{
    private $mensaje = array();
    private $guia = null;
    private $tabla = array();
    private $numeroDeSolicitud = 0;
    private $ltds = array();

    public function __construct($numeroDeSolicitud){

        $this->numeroDeSolicitud= $numeroDeSolicitud;

        
    }


    /**
     * Valida las reglas de negocio para el saldo
     * 
     * @author Javier Hernandez
     * @copyright 2022-2023 XpertaMexico
     * @package App\Negocio\Guias
     * 
     * @version 1.0.0
     * 
     * @since 1.0.0 Primera version de la funcion fedexApi
     * 
     * @throws
     *
     * @param float saldo monto disponible que tiene una empresa
     * 
     * @var int 
     * @var float $saldo
     * 
     * 
     * @return $data Se agra informacion segun la necesidad
     */

    public function index(){
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ".$this->numeroDeSolicitud);

        $this->ltds = Ltd::pluck('nombre','id');;

        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ".$this->numeroDeSolicitud);

    }


    public function getLtds(){
        return $this->ltds;
    }


}