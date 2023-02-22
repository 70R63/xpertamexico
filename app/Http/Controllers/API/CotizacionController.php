<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\ApiController as BaseController;
use Illuminate\Http\Request;
use Log;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Tarifa;
use App\Models\Sucursal;
use App\Models\Cliente;
use App\Models\EmpresaEmpresas;
use App\Models\EmpresaLtd;



class CotizacionController extends BaseController
{
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Log::info(__CLASS__." ".__FUNCTION__);
        Log::debug($request);

        
        if ( is_null($request['sucursal']) ) {
            $empresa_id = $request['clienteIdCombo'];
        } else {
            $empresa_id= Sucursal::where('id',$request['sucursal'])
                    ->value('empresa_id');
        }
        
        $empresas = EmpresaEmpresas::where('id',$empresa_id)
                ->pluck('empresa_id')->toArray();
        Log::debug($empresas);
        
        $empresasLtd = EmpresaLtd::where('empresa_id',$empresa_id)
                ->pluck('tarifa_clasificacion', 'ltd_id')->toArray();

        Log::debug($empresasLtd);
        $tabla = array();
        foreach ($empresasLtd as $ltd => $clasificacion) {
            Log::debug(" LTD $ltd => clasificacion $clasificacion");
            Log::debug($tabla);
            $tablaTmp = array();

            $query = Tarifa::select('tarifas.*', 'ltds.nombre','servicios.nombre as servicios_nombre', 'ltd_coberturas.extendida as extendida_cobertura')
                        ->join('ltds', 'tarifas.ltds_id', '=', 'ltds.id')
                        ->join('servicios','servicios.id', '=', 'tarifas.servicio_id')
                        ->join('ltd_coberturas','ltd_coberturas.ltd_id', '=', 'tarifas.ltds_id')
                        ->join('empresa_ltds', 'empresa_ltds.ltd_id', '=', 'tarifas.ltds_id')
                        ->where('tarifas.empresa_id', $empresa_id)
                        ->where('empresa_ltds.empresa_id', $empresa_id)
                        ->where('ltd_coberturas.cp', $request['cp_d'])
                        ->where('ltds.id', $ltd)
                        
                        //->toSql()
                        ;
            switch ($clasificacion) {
                case "1":
                    Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." caso 1 = FLAT");
                    $tablaTmp = $query->get()->toArray();        
                    break;
                  case "2":
                    Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." caso 2 = RANGO");

                    $tablaTmp = $query->where('kg_ini', ">=", $request['pesoFacturado'])
                        ->where('kg_fin', "<=", $request['pesoFacturado'])
                        ->get()->toArray()
                        ;
                    break;
                  default:
                    Log::debug("No se seleccion niguna clasificacion");
                }
            
            $tabla = array_merge($tabla, $tablaTmp);
        }
        
        
        Log::debug($tabla);
      
        $success['data'] = $tabla;
       
        return $this->successResponse($success, 'Cotizacion exitosa.');
        
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function cp(Request $request)
    {
        Log::info(__CLASS__." ".__FUNCTION__);
        Log::info($request);
        $modelo = $request->get('modelo');

        if ("Sucursal" == $modelo) {
            $datos = Sucursal::where("id",$request->id);
        } else {
            $datos = Cliente::where("id",$request->id);
        }
        $resultado = $datos->get()
                ->toArray();
        
        return $this->successResponse($resultado, 'User login successfully.');
        
    }

    public function store(Request $request)
    {
        $success['name'] = "nombre";
        
        return $this->successResponse($success, 'User login successfully.');
    }
}
