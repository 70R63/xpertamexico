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
                    Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." Clasificacion 1 = FLAT");
                    switch ($ltdId) {
                        case "1":
                            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ltd 1 = FEDEX");

                            $servicioIds = Tarifa::select('servicio_id')
                            ->where("ltds_id", $ltdId)
                            ->where("empresa_id", $empresa_id)
                            ->distinct()->get()->pluck('servicio_id')->toArray();

                            $tablaTmp = array();
                            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ServiciosID");
                            Log::debug(print_r($servicioIds,true));
                            foreach ($servicioIds as $key => $value) {
                                Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." servicio_id =$value");

                                $query = Tarifa::base($empresa_id, $request['cp_d'], $ltdId);
                                $query = $query->where('servicio_id', $value);
                                
                                $zona = Tarifa::fedexZona($request['cp'],$request['cp_d']);

                                if ($zona >=1 && $zona <= 4){
                                    Log::info("zONA 1 A 4");
                                    $costoZona = $query->min("costo");
                                    
                                } else {
                                    Log::info("zONA 5 A 8");
                                    $costoZona = $query->max("costo");
                                    
                                }
                                $tablaTmp = $query->where("costo",$costoZona)->get()->toArray();
                                
                                $tabla = array_merge($tabla, $tablaTmp);
                                       
                            }
                            //FIN foreach ($servicioIds as $key => $value) {
                            break;
                        default:
                            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ltd default");
                            $tablaTmp = $query->get()->toArray();
                            $tabla = array_merge($tabla, $tablaTmp);

                    }
                    //Fin switch ($ltdId) 
                    break;
                case "2":
                    Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." Clasificacion 2 = RANGO");

                    $servicioIds = Tarifa::select('servicio_id')
                        ->where("ltds_id", $ltdId)
                        ->where("empresa_id", $empresa_id)
                        ->distinct()->get()->pluck('servicio_id')->toArray();

                    Log::debug(print_r($servicioIds,true));

                    switch ($ltdId) {
                        case "1":
                            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ltd 1 = FEDEX");
                            foreach ($servicioIds as $key => $value) {
                                Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." servicio_id =$value");

                                $query = Tarifa::base($empresa_id, $request['cp_d'], $ltdId);
                                $query = $query->where('servicio_id', $value);
                                
                                $zona = Tarifa::fedexZona($request['cp'],$request['cp_d']);

                                if ($zona >=1 && $zona <= 4){
                                    Log::info("zONA 1 A 4");
                                    $costoZona = $query->min("costo");
                                    
                                } else {
                                    Log::info("zONA 5 A 8");
                                    $costoZona = $query->max("costo");
                                    
                                }
                                $tablaTmp = $query->where("costo",$costoZona)->get()->toArray();
                                
                                $tabla = array_merge($tabla, $tablaTmp);
                                       
                            }
                            //FIN foreach ($servicioIds as $key => $value) {
                        break;
                        case "2":
                            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ltd 2 = ESTAFETA");
                            foreach ($servicioIds as $key => $value) {
                                Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." servicio_id =$value");
                                $tablaTmp = array();

                                $query = Tarifa::base($empresa_id, $request['cp_d'], $ltdId);
                                $tablaTmp = $query->where( 'kg_ini', "<=", $request['pesoFacturado'] )
                                ->where('kg_fin', ">=", $request['pesoFacturado'] )
                                ->where('servicio_id', $value)
                                ->get()->toArray()
                                ;
                    
                                Log::debug(__CLASS__." ".__FUNCTION__." ".__LINE__." Validando Query Rango");
                                Log::debug(print_r($tablaTmp,true));

                                        
                                if (empty($tablaTmp)) {
                                    Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." Buscando el ultimo rango");
                                    $tarifaIdsGeneral = Tarifa::select("id", "servicio_id", "kg_fin" )
                                        ->where("empresa_id", $empresa_id)
                                        ->where("ltds_id", $ltdId)
                                        ->where("servicio_id", $value);

                                    $maxKgFin = $tarifaIdsGeneral->max("kg_fin");
                                    Log::debug(__CLASS__." ".__FUNCTION__." ".__LINE__." maxFin =$maxKgFin");
                                    $tarifaIds = $tarifaIdsGeneral
                                        ->where("kg_fin", $maxKgFin)
                                        ->get()->toArray();
                        
                                    Log::debug(print_r($tarifaIds,true));
                                    $query = Tarifa::rangoMaximo($empresa_id, $request['cp_d'], $ltdId, $tarifaIds[0]['id']);
                                    $tablaTmp = $query->get()->toArray();
                        
                                }
                                $tabla = array_merge($tabla, $tablaTmp);

                            }
                            //FIN foreach ($servicioIds as $key => $value)
                        break;
                        default:
                            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." DEFAULT");
                    }
                    //FIN switch ($ltdId) {
         
                break;
                case 3:
                    Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." Clasificacion 3 = ".Config('tarifa.clasificacion.3') );
                     $servicioIds = Tarifa::select('servicio_id')
                        ->where("ltds_id", $ltdId)
                        ->where("empresa_id", $empresa_id)
                        ->distinct()->get()->pluck('servicio_id')->toArray();

                    foreach ($servicioIds as $key => $value) {


                       $query = Tarifa::base($empresa_id, $request['cp_d'], $ltdId);
                                    $tablaTmp = $query->where( 'kg_ini', "<=", $request['pesoFacturado'] )
                                    ->where('kg_fin', ">=", $request['pesoFacturado'] )
                                    ->where('servicio_id', $value);
                        
                        $zona = Tarifa::fedexZona($request['cp'],$request['cp_d']);

                        if ($zona >=1 && $zona <= 4){
                            Log::info("zONA 1 A 4");
                            $costoZona = $query->min("costo");
                            
                        } else {
                            Log::info("zONA 5 A 8");
                            $costoZona = $query->max("costo");
                            
                        }

                        $tablaTmp = $query->where("costo",$costoZona)->get()->toArray();
                        
                        
                        $tabla = array_merge($tabla, $tablaTmp);
                    };
                break;
                default:
                    Log::debug("No se seleccion niguna clasificacion");
            }
        }
        
        Log::debug(__CLASS__." ".__FUNCTION__." ".__LINE__." Revision de tabla");
        //Log::debug($tabla);
      
        $success['data'] = $tabla;
       
        return $this->successResponse($success, 'Cotizacion exitosa.');
        
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function cp(Request $request){
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
