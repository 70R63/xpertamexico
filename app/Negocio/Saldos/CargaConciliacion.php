<?php
namespace App\Negocio\Saldos;

use Log;

//modelos
use App\Models\Ltd;
use App\Models\Saldos\CargaConciliacion as mCargaConciliacion;
use App\Models\Saldos\ConciliacionView as mConciliacionView;

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
    private $conciliacionView = array();
    private $cargaConciliacions = array();

    public function __construct($numeroDeSolicitud){

        $this->numeroDeSolicitud= $numeroDeSolicitud;
    }


    /**
     * Obtener todoas los datos iniciales apra la vista index
     * 
     * @author Javier Hernandez
     * @copyright 2022-2024 XpertaMexico
     * @package App\Negocio\Saldos
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

        $this->ltds = Ltd::pluck('nombre','id');
        $this->conciliacionView = mConciliacionView::get()->toArray();


        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ".$this->numeroDeSolicitud);

    }


    /**
     * Valida las reglas de negocio para la carga de conciliacion
     * 
     * @author Javier Hernandez
     * @copyright 2022-2024 XpertaMexico
     * @package App\Negocio\Saldos
     * 
     * @version 1.0.0
     * 
     * @since 1.0.0 Primera version de la funcion store 
     * 
     * @throws
     *
     * @param array $data Valores enviados en el request
     * 
     * @var int 
     * @var float $saldo
     * 
     * 
     * @return void
     */

    public function store(array $data){
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ".$this->numeroDeSolicitud);

        $ltd_id= $data["ltd_id"];
        $fechaFactura = $data["fecha_factura"];;
        $file=$data['fileCargaConciliacion'];
        $fileNombre = $file->getClientOriginalName();
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ".$this->numeroDeSolicitud."-$fileNombre");

        $csvFile = fopen($file->getRealPath(), "r");
        fgetcsv($csvFile);
        
        while (($row = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
            Log::debug(print_r($row,true));

            $rowInsert = [
                "user_id" => auth()->user()->id,
                "empresa_id" => auth()->user()->empresa_id,
                "fecha_factura" => $fechaFactura,
                "ltd_id" => $ltd_id,
                "tracking_number" => $row[0],
                "num_factura_ltd" => $row[1],
                "servicio_id" => $row[2],
                "fecha_envio" => $row[3],
                "peso_facturado_ltd" => $row[4],
                "alto_facturado_ltd" => $row[5],
                "largo_facturado_ltd" => $row[6],
                "ancho_facturado_ltd" => $row[7],
                "subtotal_facturado_ltd" => $row[8],
                "total_facturado_ltd" => $row[9],
                "adicional_ae_ltd" => $row[10],
                "adicional_seguro_ltd" => $row[11],
                "adicional_envio_irregular_ltd" => $row[12],
                "adicional_correcion_ltd" => $row[13],
                "adicional_exceso_dimension_ltd" =>$row[14] ,

            ];

            mCargaConciliacion::create($rowInsert);
        }
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ".$this->numeroDeSolicitud);

    }


    /**
     * Obtener todos los registros de una factura en especifico
     * 
     * @author Javier Hernandez
     * @copyright 2022-2024 XpertaMexico
     * @package App\Negocio\Saldos
     * 
     * @version 1.0.0
     * 
     * @since 1.0.0 Primera version de la funcion show
     * 
     * @throws
     *
     * @param string valor de factura par cada LTD
     * 
     * @var array ltds  
     * @var float $saldo
     * 
     * 
     * @return void Se usaran getters y settes
     */

    public function show( $facturaId){
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ".$this->numeroDeSolicitud);

        $this->ltds = Ltd::pluck('nombre','id');
        $this->cargaConciliacions = mCargaConciliacion::where("num_factura_ltd", $facturaId)->get()->toArray();


        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ".$this->numeroDeSolicitud);

    }

    public function getLtds(){
        return $this->ltds;
    }

    public function getConciliacionView(){
        return $this->conciliacionView;
    }

    public function getCargaConciliacions(){
        return $this->cargaConciliacions;
    }


}