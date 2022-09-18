<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use Log;

class Direccion extends Model
{
    use HasFactory;

    protected $table = 'direcciones';
    protected $guarded = []; 

    /**
     * Agraga a la consulta los casos de negocio.
     *
     * 
    */

    protected static function boot()
    {
        Log::info("funcion boot");
        parent::boot();        
        static::addGlobalScope('estatus', function (Builder $builder) {
            $builder->where('direcciones.estatus', '1');
        });
    }
}
