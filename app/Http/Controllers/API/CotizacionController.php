<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\ApiController as BaseController;
use Illuminate\Http\Request;
use Log;
use Laravel\Sanctum\HasApiTokens;
use DB;

use App\Models\Sucursal;
use App\Models\Cliente;

use App\Negocio\Guias\Cotizacion as nCotizacion;



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

        
        $nCotizacion = new nCotizacion();
        $nCotizacion->base($request);
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        $tabla = $nCotizacion->getTabla();

        Log::debug(__CLASS__." ".__FUNCTION__." ".__LINE__." Revision de tabla");
        Log::debug(print_r($tabla,true));
        $success['data'] = $tabla;
        $success['saldo'] = $nCotizacion->getSaldo();
        $success['tipoPagoId'] = $nCotizacion->getTipoPagoId();
       
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
