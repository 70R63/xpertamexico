<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Log;
class Reportes_ventas extends Model
{
    use HasFactory;


    public function scopeFiltro($query, $parametros) {
        
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
      

        if ( !empty($parametros['ltdId']) ) {
            $query->where('ltd_id', $parametros['ltdId']);
        }

        if ( !empty($parametros['clienteIdCombo']) ) {
            $query->where('cia', $parametros['clienteIdCombo']);
        }

        if ( !empty($parametros['servicio_id']) ) {
            //$query->where('servicio_id', $parametros['servicio_id']);
        }
        /*
        if ( !empty($parametros['servicio_id']) ) {
            $query->where('servicio_id', $parametros['servicio_id']);
        }
        */

        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        return $query;
        
   }
}
