<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Log;
use Carbon\Carbon;

use App\Models\API\Sucursal;

class Reportes_ventas extends Model
{
    use HasFactory;


    public function scopeFiltro($query, $parametros) {
        
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
      

        if ( !empty($parametros['ltdId']) ) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
            $query->where('ltd_id', $parametros['ltdId']);
        }

        if ( !empty($parametros['clienteIdCombo']) ) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
            $empresas = Sucursal::where('empresa_id',$parametros['clienteIdCombo'])->pluck('id')->toArray();
            $query->whereIN('cia', $empresas);
        }

        if ( !empty($parametros['servicio_id']) ) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
            $query->where('servicio_id', $parametros['servicio_id']);
        }
        
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        if ( !empty($parametros['fecha_ini']) ) {

            $carbon = Carbon::parse($parametros['fecha_ini'])->format('Y-m-d');
            
            Log::debug($carbon);
            $query->where('created_at','>=', $carbon." 00:00:00");
        }

        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        if ( !empty($parametros['fecha_fin']) ) {

            $carbon = Carbon::parse($parametros['fecha_fin'])->format('Y-m-d');
            //$carbon->settings(['toStringFormat' => 'Y-m-d-H-i-s']);
            Log::debug($carbon);
            $query->where('created_at','<=', $carbon." 23:59:59");
        }
        

        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        return $query;
        
   }
}
