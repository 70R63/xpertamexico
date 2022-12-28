<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use Log;
use App\Models\Sucursal;

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

    /**
     * Funcion para crear un objeto para insertar el registro del cliente.
     *
     * @param $request
     * @return array 
     * 
    */

    public function insertManual($request){
        Log::info(__CLASS__." ".__FUNCTION__." INICIANDO ---------");

        $sucusalId = $request['sucursal_id'];
        $sucursalPluck = Sucursal::where("id",$sucusalId)->pluck("empresa_id","id");
        
        $insert = array(
            "nombre"    => $request['nombre_d']
            ,"contacto" => $request['contacto_d']
            ,"direccion"=> $request['direccion_d']
            ,"direccion2"=>$request['direccion2_d']
            ,"cp"       => $request['cp_d']
            ,"colonia"  => $request['colonia_d']
            ,"ciudad"   => $request['ciudad_d']
            ,"entidad_federativa"=>$request['entidad_federativa_d']
            ,"celular"  => $request['celular_d']
            ,"telefono" => $request['telefono_d']
            ,"empresa_id"=>$sucursalPluck[$sucusalId]
            ,"no_ext"   => $request['no_ext_d']
            ,"no_int"   => $request['no_int_d']

            );

        Log::info(__CLASS__." ".__FUNCTION__." FINALIZANDO ---------");
        return $this->create($insert)->id;

    }    
}
