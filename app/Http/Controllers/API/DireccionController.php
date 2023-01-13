<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\ApiController;
use App\Models\Cliente;
use App\Models\Sucursal;

use Log;

class DireccionController extends ApiController
{
    /**
     * Index,  api
     *
     * @return \Illuminate\Http\Response
     */
    public function index($tipo)
    {
        Log::info(__CLASS__." ".__FUNCTION__." INICIANDO-----------------");
        Log::debug($tipo);

        try {
            if($tipo === "destinatario"){
                $tabla = Cliente::get()->toArray();    
            }else{
                $tabla = Sucursal::get()->toArray();
            }
            

            $success['mensaje'] = "Asignacion exitosa";

            return $this->successResponse($tabla, 'User login successfully.');

        } catch(\Illuminate\Database\QueryException $e){ 
            Log::info(__CLASS__." ".__FUNCTION__." QueryException");
            Log::debug($e->getMessage()); 
            $mensaje = $e->getMessage();

        } catch (\Exception $e) {
            Log::info(__CLASS__." ".__FUNCTION__." Exception");
            Log::debug( $e->getMessage() );
            $mensaje = $e->getMessage();
        }
        Log::info(__CLASS__." ".__FUNCTION__." FINALIZANDO-----------------");
        return $this->sendError($mensaje);
        
    }
}
