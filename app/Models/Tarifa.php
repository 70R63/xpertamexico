<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Log;

use App\Models\PostalZona;
use App\Models\PostalGrupo;
use App\Models\LtdCobertura;
use App\Models\Servicio;



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

        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);

        //1 indica que puede enviar en todas los servicios de las tarifas
        $prioridad = 1;
        //LTD 2= estafeta
        if ($ltdId == 2 ) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
            $ltdCobertura = LtdCobertura::select('garantia','id')
                    ->where('ltd_id',$ltdId)
                    ->where('cp',$cp_d)
                    ->get()->toArray()[0];
            Log::debug(print_r($ltdCobertura,true));

            $servicio = Servicio::where('nombre', 'like',$ltdCobertura['garantia'] )
                    ->where('estatus',1)
                    ->get()->toArray()[0];

            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
            Log::debug(print_r($servicio,true));
            $prioridad = $servicio['prioridad'];
        }

        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        return $query->select('tarifas.*', 'ltds.nombre','servicios.nombre as servicios_nombre', 'ltd_coberturas.extendida as extendida_cobertura','ltd_coberturas.ocurre' )
                ->join('ltds', 'tarifas.ltds_id', '=', 'ltds.id')
                ->join('servicios','servicios.id', '=', 'tarifas.servicio_id')
                ->join('ltd_coberturas','ltd_coberturas.ltd_id', '=', 'tarifas.ltds_id')
                ->join('empresa_ltds', 'empresa_ltds.ltd_id', '=', 'tarifas.ltds_id')
                ->where('tarifas.empresa_id', $empresa_id)
                ->where('empresa_ltds.empresa_id', $empresa_id)
                ->where('ltd_coberturas.cp', $cp_d)
                ->where('ltds.id', $ltdId)
                ->where('servicios.prioridad','>=', $prioridad)
                
                ;
        
    }

    /**
     * Cuandola clasificaion es Rango y no hay rango posible se toma como tarifa el rango mayor de cada servicio
     */
    public function scopeRangoMaximo($query, $empresa_id, $cp_d, $ltdId, $tarifaId)
    {

        return $query->select('tarifas.*', 'ltds.nombre','servicios.nombre as servicios_nombre', 'ltd_coberturas.extendida as extendida_cobertura','ltd_coberturas.ocurre')
                ->join('ltds', 'tarifas.ltds_id', '=', 'ltds.id')
                ->join('servicios','servicios.id', '=', 'tarifas.servicio_id')
                ->join('ltd_coberturas','ltd_coberturas.ltd_id', '=', 'tarifas.ltds_id')
                ->join('empresa_ltds', 'empresa_ltds.ltd_id', '=', 'tarifas.ltds_id')
                ->where('tarifas.empresa_id', $empresa_id)
                ->where('empresa_ltds.empresa_id', $empresa_id)
                ->where('ltd_coberturas.cp', $cp_d)
                ->where('tarifas.id', $tarifaId)

                ;
        
    }


    /**
     * Se obtiene las zonas y grupos para saber cual tarifa entregar en Fedex con servicio Flat
     */
    public function scopeFedexZona($query, $cp, $cp_d)
    {
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
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
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        return $zona[0]; 

    }

}
