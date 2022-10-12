<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Log;

class Tarifa extends Model
{
    use HasFactory;

    protected $fillable =['id','ltds_id','servicio_id','kg_ini','kg_fin','kg_extra','extendida', 'costo', 'empresa_id'];

    /**
     * Agraga a la consulta los casos de negocio.
     *
     * 
         */

    protected static function boot()
    {
        parent::boot();        
        static::addGlobalScope('status', function (Builder $builder) {
            $builder->where('tarifas.estatus', '1');
            $builder->where('tarifas.empresa_id', auth()->user()->empresa_id);
        });
    }
}
