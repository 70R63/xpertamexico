<?php

namespace App\Models\Saldos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Log;

class Pagos extends Model
{
    use HasFactory;

    protected $casts = [
        'fecha_deposito'  => 'date:Y-m-d',
    
    ];
    
    protected $fillable = ['empresa_id','tipo_pago_id','banco_id', 'referencia', 'importe', 'fecha_deposito', 'hora_deposito'];


    public function scopeJoinEmpresa($query) {
      Log::info(__CLASS__." ".__FUNCTION__);
      return $query->join('empresas', 'empresas.id', '=', 'empresa_id');
    }
    
    public function scopeJoinBancos($query) {
      Log::info(__CLASS__." ".__FUNCTION__);
      return $query->join('bancos', 'bancos.id', '=', 'banco_id');
    }
}
