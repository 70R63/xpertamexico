<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Log;

class Tarifa extends Model
{
    use HasFactory;

    protected $guarded = [];

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
