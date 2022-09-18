<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Log;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Tarifa;
use App\Models\Sucursal;



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
        Log::info($request->get('peso'));

        $tabla = Tarifa::where('tarifas.estatus',1)
                    ->where('kg_ini', '<=', $request->get('peso') )
                    ->where('kg_fin', '>=', $request->get('peso') )
                    ->join('ltds', 'tarifas.ltds_id', '=', 'ltds.id')
                    ->select('tarifas.*', 'ltds.nombre'
                        ,\DB::raw('(tarifas.kg_extra+tarifas.extendida+tarifas.costo) as costo_total'))
                    ->get()->toArray();
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

        $datos = Sucursal::where("id",$request->id)
                ->get()
                ->toArray();
        $success = $datos;
        Log::info($success);
        return $this->sendResponse($success, 'User login successfully.');
        
    }

    public function store(Request $request)
    {
        $success['name'] = "nombre";
        
        return $this->sendResponse($success, 'User login successfully.');
    }
}
