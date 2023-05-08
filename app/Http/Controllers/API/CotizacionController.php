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
use App\Models\LtdCobertura;
use App\Models\PostalGrupo;
use App\Models\PostalZona;
use App\Models\DhlTarifas;
use App\Models\Empresa;



class CotizacionController extends BaseController
{
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        Log::debug($request);

        
        if ( is_null($request['sucursal']) ) {
            $empresa_id = $request['clienteIdCombo'];
        } else {
            $empresa_id= Sucursal::where('id',$request['sucursal'])
                    ->value('empresa_id');
        }
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." EmpresaEmpresas");
        $empresas = EmpresaEmpresas::where('id',$empresa_id)
                ->pluck('empresa_id')->toArray();
        Log::debug($empresas);
        
        $empresasLtd = EmpresaLtd::where('empresa_id',$empresa_id)
                ->pluck('tarifa_clasificacion', 'ltd_id')->toArray();

        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." validando empresaLTD");
        Log::debug($empresasLtd);
        $tabla = array();
        foreach ($empresasLtd as $ltdId => $clasificacion) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." LTD $ltdId => clasificacion $clasificacion ------------------------------------");
                        
            $tablaTmp = array();
        
            $query = Tarifa::base($empresa_id, $request['cp_d'], $ltdId);
            switch ($clasificacion) {
                case "1": //FLAT
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
                                
                                $tablaTmp[0]['zona']=$zona;
                                $tabla = array_merge($tabla, $tablaTmp);
                                       
                            }
                            //FIN foreach ($servicioIds as $key => $value) {
                            break;
                        default:
                            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ltd default");
                            $tablaTmp = $query->get()->toArray();

                            foreach ($tablaTmp as $key => $value) {
                                $tablaTmp[$key]['zona'] = "NA";
                            }

                            $tabla = array_merge($tabla, $tablaTmp);

                    }
                    //Fin switch ($ltdId) 
                break;
                case "2"://RANGO
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
                                $tablaTmp = $query->where( 'kg_ini', "<=", $request['pesoFacturado'] )
                                ->where('kg_fin', ">=", $request['pesoFacturado'] )
                                ->where('servicio_id', $value)
                                ->get()->toArray()
                                ;

                                foreach ($tablaTmp as $key => $value) {
                                    $tablaTmp[$key]['zona'] = "NA";
                                }

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

                                foreach ($tablaTmp as $key => $value) {
                                    $tablaTmp[$key]['zona'] = "NA";
                                }
                                $tabla = array_merge($tabla, $tablaTmp);

                            }
                            //FIN foreach ($servicioIds as $key => $value)
                        break;
                        case "3":
                            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ltd ".Config('ltd.redpack.id')."=".Config('ltd.redpack.nombre') );
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
                                foreach ($tablaTmp as $key => $value) {
                                    $tablaTmp[$key]['zona'] = "NA";
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
                case 3: //RANGO ZONA
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
                        foreach ($tablaTmp as $key => $value) {
                            $tablaTmp[$key]['zona'] = "NA";
                        }
                        $tabla = array_merge($tabla, $tablaTmp);
                    };
                break;
                case 4: //RANGO FLAT
                    Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." Clasificacion 4 = ".Config('tarifa.clasificacion.4') );

                    $estadoCoberturaOrigen = LtdCobertura::select('estado', 'extendida')
                            ->where('ltd_id',Config('ltd.dhl.id'))
                            ->where('cp',$request['cp'])
                            ->get()->toArray()
                            ;


                    $estadoCoberturaDestino = LtdCobertura::select('estado', 'extendida', 'ocurre' )
                            ->where('ltd_id',Config('ltd.dhl.id'))
                            ->where('cp',$request['cp_d'])
                            ->get()->toArray()
                            ;

                    if ( !(count($estadoCoberturaOrigen) * count($estadoCoberturaDestino) )  ) {
                        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
                        Log::debug("No se cuenta con cobertura");
                        break;
                    }
                    

                    Log::debug(print_r($estadoCoberturaDestino,true));
                    $postalGrupoOrigen = PostalGrupo::select('grupo')
                            ->where('ltd_id',Config('ltd.dhl.id'))
                            ->where('entidad_federativa',$estadoCoberturaOrigen[0])
                            ->get()->pluck('grupo')->toArray()
                            ;

                    $postalGrupoDestino = PostalGrupo::select('grupo')
                            ->where('ltd_id',Config('ltd.dhl.id'))
                            ->where('entidad_federativa',$estadoCoberturaDestino[0])
                            ->get()->pluck('grupo')->toArray()
                            ;
                    Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
                    Log::debug("Grupos Postales ".$postalGrupoOrigen[0]." ".$postalGrupoDestino[0]);
                    $zona = PostalZona::select('zona')
                            ->where('ltd_id',Config('ltd.dhl.id'))
                            ->where('grupo_origen',$postalGrupoOrigen[0])
                            ->where('grupo_destino', $postalGrupoDestino[0])
                            ->get()->pluck('zona')->toArray()
                            ;

                    Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
                    Log::debug("Zona ".$zona[0]);
                    Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
                    $tarifas = DhlTarifas::select('precio', 'id', 'servicio_id')
                            ->where('kg', $request['pesoFacturado'])
                            ->where('zona',$zona[0] )
                            ->get()->toArray()
                            ;

                    foreach ($tarifas as $key => $tarifa) {
                        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
                        Log::debug(print_r($tarifa,true));  

                        $empresa = Empresa::where('id', $empresa_id)->get()->toArray()[0];
                        Log::debug(print_r($empresa,true));

                        $descuentoPorcentaje = $empresa['descuento']/100;
                        $costoDescuento = round($tarifa['precio'] *$descuentoPorcentaje,2);
                        
                        $subCosto = $tarifa['precio']-$costoDescuento;
                        Log::debug(print_r($subCosto,true));

                        $fscIncremento = $empresa['fsc']/100;
                        Log::debug(print_r($fscIncremento,true)); 
                        
                        $costoFsc = round( $subCosto*$fscIncremento ,2);
                        Log::debug(print_r($costoFsc,true)); 
                        $costo = round( $subCosto*(1+$fscIncremento) ,2);

                        if ($request['piezas']>1){
                            $costo = $costo+ $empresa['precio_mulitpieza'];
                        }

                        if ($estadoCoberturaDestino[0]['extendida'] === "SI" ) {
                            $costo = round( $costo + $empresa['area_extendida'] ,2);
                        }

                        $servicioNombre = ($tarifa['servicio_id'] ===2) ? 'Dia Sig' : 'Terrestre' ;
                        $tablaTmp = array('id' => $tarifa['id']
                            ,'costo'    => $costo
                            ,'ltds_id' => Config('ltd.dhl.id')
                            ,'nombre' => Config('ltd.dhl.nombre')
                            ,'servicios_nombre' => $servicioNombre
                            ,'kg_ini' => $request['pesoFacturado']
                            ,'kg_fin' => $request['pesoFacturado']
                            ,'kg_extra' => 0
                            ,'ocurre'   => $estadoCoberturaDestino[0]['ocurre']
                            ,'extendida_cobertura'=>$estadoCoberturaDestino[0]['extendida'] 
                            ,'extendida'    => $empresa['area_extendida']
                            ,'servicio_id'  =>$tarifa['servicio_id']
                            ,'seguro'   => $empresa['seguro']
                            ,'zona'     => $zona[0]

                            );

                        $tabla[] = $tablaTmp;

                        if ($tarifa['servicio_id']===2) {
                            if ($empresa['premium10'] > 0){
                                $tablaTmp['costo'] = round($tablaTmp['costo']+$empresa['premium10'],2);
                                $tablaTmp['servicios_nombre'] = "10:30";
                                $tablaTmp['servicio_id'] = "5";

                                $tabla[] = $tablaTmp;
                            }

                            if ($empresa['premium12'] > 0){
                                $tablaTmp['costo'] = round($tablaTmp['costo']+$empresa['premium12'],2);
                                $tablaTmp['servicios_nombre'] = "12:00";
                                $tablaTmp['servicio_id'] = "6";
                                $tabla[] = $tablaTmp;
                            }
                        }
                    }
                    
                    Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__); 
                break;
                default:
                    Log::debug("No se seleccion niguna clasificacion");
            }
        }
        
        Log::debug(__CLASS__." ".__FUNCTION__." ".__LINE__." Revision de tabla");
        Log::debug(print_r($tabla,true));
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
