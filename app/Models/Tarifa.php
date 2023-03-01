<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Log;

use App\Models\PostalZona;
use App\Models\PostalGrupo;

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

            $empresas = EmpresaEmpresas::where('id',auth()->user()->empresa_id)
                ->pluck('empresa_id')->toArray();
            $builder->whereIN('tarifas.empresa_id',$empresas);

        });
    }

    /**
     * Se crea un scope con la base del query para tarifas con difernetes forams de tarificar
     */
    public function scopeBase($query, $empresa_id, $cp_d, $ltdId )
    {

        return $query->select('tarifas.*', 'ltds.nombre','servicios.nombre as servicios_nombre', 'ltd_coberturas.extendida as extendida_cobertura')
                ->join('ltds', 'tarifas.ltds_id', '=', 'ltds.id')
                ->join('servicios','servicios.id', '=', 'tarifas.servicio_id')
                ->join('ltd_coberturas','ltd_coberturas.ltd_id', '=', 'tarifas.ltds_id')
                ->join('empresa_ltds', 'empresa_ltds.ltd_id', '=', 'tarifas.ltds_id')
                ->where('tarifas.empresa_id', $empresa_id)
                ->where('empresa_ltds.empresa_id', $empresa_id)
                ->where('ltd_coberturas.cp', $cp_d)
                ->where('ltds.id', $ltdId)
                ;
        
    }

    /**
     * Cuandola clasificaion es Rango y no hay rango posible se toma como tarifa el rango mayor de cada servicio
     */
    public function scopeRangoMaximo($query, $empresa_id, $cp_d, $ltdId, $tarifaId)
    {

        return $query->select('tarifas.*', 'ltds.nombre','servicios.nombre as servicios_nombre', 'ltd_coberturas.extendida as extendida_cobertura')
                ->join('ltds', 'tarifas.ltds_id', '=', 'ltds.id')
                ->join('servicios','servicios.id', '=', 'tarifas.servicio_id')
                ->join('ltd_coberturas','ltd_coberturas.ltd_id', '=', 'tarifas.ltds_id')
                ->join('empresa_ltds', 'empresa_ltds.ltd_id', '=', 'tarifas.ltds_id')
                ->where('tarifas.empresa_id', $empresa_id)
                ->where('empresa_ltds.empresa_id', $empresa_id)
                ->where('ltd_coberturas.cp', $cp_d)
                //->where('ltds.id', $ltdId)
                ->where('tarifas.id', $tarifaId)

                ;
        
    }


    /**
     * Se obtiene las zonas y grupos para saber cual tarifa entregar en Fedex con servicio Flat
     */
    public function scopeFedexZona($query, $cp, $cp_d)
    {

        $postalGrupoOrigen = PostalGrupo::where("cp_inicial", "<=", $cp)
            ->where("cp_final", ">=", $cp)
            ->pluck("grupo")->toArray();

        $postalGrupoDestino = PostalGrupo::where("cp_inicial", "<=", $cp_d)
            ->where("cp_final", ">=", $cp_d)
            ->pluck("grupo")->toArray();

        Log::info($postalGrupoOrigen);
        Log::info($postalGrupoDestino);


        $zona = PostalZona::where("grupo_origen", $postalGrupoOrigen)
            ->where("grupo_destino", $postalGrupoDestino)
            ->pluck("zona")->toArray();

        return $zona[0]; 

    }

}
