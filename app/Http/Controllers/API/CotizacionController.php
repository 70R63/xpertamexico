<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\ApiController as BaseController;
use Illuminate\Http\Request;
use Log;
use Laravel\Sanctum\HasApiTokens;
use DB;

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

        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." validando empresaLTD");
        Log::debug($empresasLtd);
        $tabla = array();
        foreach ($empresasLtd as $ltdId => $clasificacion) {
            Log::debug(" LTD $ltdId => clasificacion $clasificacion");
            
            $tablaTmp = array();
        
            $query = Tarifa::base($empresa_id, $request['cp_d'], $ltdId);
            switch ($clasificacion) {
                case "1":
                    Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." caso 1 = FLAT");
                    $tablaTmp = $query->get()->toArray();
                    $tabla = array_merge($tabla, $tablaTmp);        
                    break;
                case "2":
                    Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." caso 2 = RANGO");

                    $servicioIds = Tarifa::select('servicio_id')
                        ->where("ltds_id", $ltdId)
                        ->distinct()->get()->pluck('servicio_id')->toArray();

                    Log::debug(print_r($servicioIds,true));

                    foreach ($servicioIds as $key => $value) {
                        $tablaTmp = $query->where( 'kg_ini', "<=", $request['pesoFacturado'] )
                        ->where('kg_fin', ">=", $request['pesoFacturado'] )
                        ->where('servicio_id', $value)
                        ->get()->toArray()
                        ;

                        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." caso 2 = RANGO con servicio_id =$value");
                        if (empty($tablaTmp)) {
                            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." Buscando el ultimo rango");

                            $tarifaIdsGeneral = Tarifa::select("id", "servicio_id", "kg_fin" )
                                ->where("empresa_id", $empresa_id)
                                ->where("ltds_id", $ltdId)
                                ->where("servicio_id", $value);

                            $maxKgFin = $tarifaIdsGeneral->max("kg_fin");

                            $tarifaIds = $tarifaIdsGeneral
                                ->where("kg_fin", $maxKgFin)
                                ->get()->toArray();

                            $query = Tarifa::rangoMaximo($empresa_id, $request['cp_d'], $ltdId, $tarifaIds[0]['id']);

                            $tablaTmp = $query->get()->toArray();
                
                        }
                        $tabla = array_merge($tabla, $tablaTmp);

                    }
                    
                    break;
                default:
                    Log::debug("No se seleccion niguna clasificacion");
            }
        }
        
        Log::debug(__CLASS__." ".__FUNCTION__." ".__LINE__." Revision de tabla");
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

    private function queryBaseTarifa()
    {
        $success['name'] = "nombre";
        
        return $this->successResponse($success, 'User login successfully.');
    }
}
