<?php

namespace App\Negocio;

use Log;
use App\Models\Tarifa;

class Fedex_tarifas 
{
    private $tarifa = array();
    
    /**
     * Funcion para poder obtener la cotizacion ligado a la zona de FEDEX
     * 
     * @param \Illuminate\Http\Request $request
     * @return array body
     */

    public function zona($request, $servicioIds,$empresa_id,$ltdId){
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);

        $zona = Tarifa::fedexZona($request['cp'],$request['cp_d']);

        foreach ($servicioIds as $key => $value) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." servicio_id =$value");
            $query = Tarifa::base($empresa_id, $request['cp_d'], $ltdId);
            $query->where('servicio_id', $value)
                        ->where("zona",$zona)
                        ->where( 'kg_ini', "<=", $request['pesoFacturado'] )
                        ->where('kg_fin', ">=", $request['pesoFacturado'] )
                    ;
            $tablaTmp = $query->get()->toArray();
            $this->tarifa = array_merge($this->tarifa, $tablaTmp);
            //Log::debug(print_r($query->get()->toArray(),true));
            //Log::debug(print_r($query->toSql(),true));
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
            Log::debug(print_r($zona,true));
        }
       
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);

    }


    public function getTarifa(){
        return $this->tarifa;
    }
}
