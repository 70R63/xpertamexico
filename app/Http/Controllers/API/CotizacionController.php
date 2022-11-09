<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Log;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Tarifa;
use App\Models\Sucursal;
use App\Models\Cliente;




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

        $empresa_id= Sucursal::where('id',$request['sucursal'])
                    ->value('empresa_id');
        Log::debug($empresa_id);
        $tabla = Tarifa::select('tarifas.*', 'ltds.nombre','servicios.nombre as servicios_nombre')
                    ->join('ltds', 'tarifas.ltds_id', '=', 'ltds.id')
                    
                    ->join('servicios','servicios.id', '=', 'tarifas.servicio_id')
                    ->where('tarifas.empresa_id', $empresa_id)
                    ->get()->toArray()
                    //->toSql()
                    ;

        Log::debug($tabla);
        $success['data'] = $tabla;
        Log::info($success);
        return $this->sendResponse($success, 'User login successfully.');
        
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

        
        return $this->sendResponse($resultado, 'User login successfully.');
        
    }

    public function store(Request $request)
    {
        $success['name'] = "nombre";
        
        return $this->sendResponse($success, 'User login successfully.');
    }
}
