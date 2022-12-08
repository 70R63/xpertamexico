<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Log;
use Laravel\Sanctum\HasApiTokens;

use App\Models\EmpresaLtd;


class EmpresaLtdController extends BaseController
{
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Log::info(__CLASS__." ".__FUNCTION__);
        
        $success['data'] = array();
        Log::info($success);
        return $this->sendResponse($success, 'User login successfully.');
        
    }

 
    public function store(Request $request)
    {     
        Log::info(__CLASS__." ".__FUNCTION__);
        $mensaje = "";
        $empresa_id = $request->empresa_id;
        Log::debug($request);

        try {

            $objeto = EmpresaLtd::where('empresa_id',$empresa_id)->delete();
            
            foreach ($request->except(['_token','empresa_id']) as $key => $value) {
                Log::debug("$key => $value");
                EmpresaLtd::create(array('empresa_id' => $empresa_id
                        ,'ltd_id' => $value ));
            }

            $success['mensaje'] = "Asignacion exitosa";

            return $this->sendResponse($success, 'User login successfully.');

        } catch(\Illuminate\Database\QueryException $e){ 
            Log::info(__CLASS__." ".__FUNCTION__." "."QueryException");
            Log::debug($e->getMessage()); 
            $mensaje = $e->getMessage();

        } catch (Exception $e) {
            Log::info(__CLASS__." ".__FUNCTION__." "."Exception");
            Log::debug( $e->getMessage() );
            $mensaje = $e->getMessage();
        }
        
        return $this->sendError($mensaje);
        
    }
}
