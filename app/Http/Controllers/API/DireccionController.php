<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\ApiController;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Sucursal;
use App\Models\API\Sucursal as SucursalApi;
use App\Models\API\Cliente as ClienteApi;

use Log;
use Carbon\Carbon;

use Illuminate\Validation\ValidationException;

class DireccionController extends ApiController
{
    /**
     * Index,  api
     *
     * @return \Illuminate\Http\Response
     */
    public function index($tipo,Request $request)
    {
        $numeroDeSolicitud = Carbon::now()->timestamp;
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." sd=$numeroDeSolicitud INICIANDO----------------- }");
        Log::debug(__CLASS__." ".__FUNCTION__." ".__LINE__." sd=$numeroDeSolicitud, $tipo");

        $data = $request->toArray();
        Log::debug(__CLASS__." ".__FUNCTION__." ".__LINE__." sd=$numeroDeSolicitud ".print_r($data,true));

        try {

            switch ($tipo) {
                case 'destinatario':
                    //Destino
                    if ( count($data) <=0 ) {
                        Log::debug(__CLASS__." ".__FUNCTION__." ".__LINE__." sd=$numeroDeSolicitud");
                        $tabla = Cliente::get()->toArray();
                    } else {
                        Log::debug(__CLASS__." ".__FUNCTION__." ".__LINE__." sd=$numeroDeSolicitud");
                        $tabla = Cliente::where("empresa_id",$data['empresa_id'])->get()->toArray();
                    }
                    
                    break;

                case 'remitente':
                    //Origen
                    if ( count($data) <=0 ) {
                        Log::debug(__CLASS__." ".__FUNCTION__." ".__LINE__." sd=$numeroDeSolicitud");
                        $tabla = Sucursal::get()->toArray();
                    } else {
                        Log::debug(__CLASS__." ".__FUNCTION__." ".__LINE__." sd=$numeroDeSolicitud");
                        $tabla = Sucursal::where("empresa_id",$data['empresa_id'])->get()->toArray();
                    }
                    
                    break;
                
                default:
                    Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
                    $sucursal = SucursalApi::where('id',$tipo)
                            ->get()->toArray();

                    $tabla = ClienteApi::where('empresa_id',$sucursal[0]['empresa_id'])
                        ->orderBy('nombre')
                        ->get()->toArray();
                   
                    // code...
                    break;
            }


            $success['mensaje'] = "Asignacion exitosa";
            Log::debug(__CLASS__." ".__FUNCTION__." ".__LINE__);
            return $this->successResponse($tabla, "$tipo Solicitud Exitosa");

        } catch(\Illuminate\Database\QueryException $e){ 
            Log::info(__CLASS__." ".__FUNCTION__." QueryException");
            Log::debug($e->getMessage()); 
            $mensaje = $e->getMessage();

        } catch (ValidationException $e) {
            Log::info(__CLASS__." ".__FUNCTION__.__LINE__." sd=$numeroDeSolicitud, ValidationException");
            Log::debug(print_r($e->getMessage(),true));
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
