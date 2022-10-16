<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class LtdSesion extends Model
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
        static::addGlobalScope('ltd_empresa', function (Builder $builder) {
            $builder->where('ltd_sesions.empresa_id', auth()->user()->empresa_id);
        
        });
    }
}
