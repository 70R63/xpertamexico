<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use Log;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';
    protected $guarded = []; 

    /**
     * Agraga a la consulta los casos de negocio.
     *
     * 
    */

    protected static function boot()
    {
        parent::boot();        
        static::addGlobalScope('estatus', function (Builder $builder) {
            $builder->where('clientes.estatus', '1');

            $empresas = EmpresaEmpresas::where('id',auth()->user()->empresa_id)
                ->pluck('empresa_id')->toArray();
            $builder->whereIN('empresa_id',$empresas);
        });
    }
}
