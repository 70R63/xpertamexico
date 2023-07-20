<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\ApiController;
use Illuminate\Http\Request;
use App\Http\Requests\StoreReportesRequest;
use App\Http\Requests\UpdateReportesRequest;
use App\Models\API\Reportes_ventas;
use App\Models\API\Reportes;

use Log;
use File;
use Carbon\Carbon;

class ReportesController extends ApiController
{
    /**
     * Display a listing of the resource.
     * @method GET
     *
     * @return \Illuminate\Http\Response
     */
    
    public function ventas(Request $request)
    {
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        Log::debug(print_r($request->all(),true));

        $reporteVentas = Reportes::get()->toArray();

        try {

            $resultado = array();
            $mensaje = "ok";
            return $this->successResponse($reporteVentas, $mensaje);    

        } catch (\InvalidArgumentException $ex) {
            Log::debug($ex );
            $mensaje = $ex->getMessage();

        } catch (\ErrorException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." ErrorException");
            Log::debug(print_r($ex,true));
            
            $mensaje =$ex->getMessage();

        } catch (\HttpException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." HttpException");
            $resultado = $ex;
            $mensaje = $ex->getMessage();
        } catch (\Exception $e) {
            Log::info(__CLASS__." ".__FUNCTION__." Exception");
            Log::debug(print_r($e->getMessage(),true ));
           $mensaje = $e->getMessage();
        }
        Log::info(__CLASS__." ".__FUNCTION__." FINALIZANDO-----------------");
        return $this->sendError("Exception",$mensaje, "400");

    }


    /**
     * Display a listing of the resource.
     * 
     * @method POST
     *
     * @return \Illuminate\Http\Response
     */
    
    public function creacion(Request $request)
    {
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        $parametros = $request->all();
        Log::debug(print_r($parametros,true));


        $reporteVentas = Reportes_ventas::filtro( $parametros )
            ->get()->toArray()
            
        ;

        //Log::debug($reporteVentas->toSql());
        
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        
        $headers = array(
            'Content-Type' => 'text/csv'
        );

        $carbon = Carbon::parse();
        $unique = md5( (string)$carbon);
        $carbon->settings(['toStringFormat' => 'Y-m-d-H-i-s']);
        $nameCsv = sprintf("csv/%s-%s.csv",(string)$carbon,$unique);

        $filename =  public_path($nameCsv);
        $handle = fopen($filename, 'w');


        fputcsv($handle, [
            "id",
            "empresa_id"
            ,"usuario"
            ,"ltd_id"
            ,"trackingNumber"
            ,"servicio_id"
            ,"empresa"
            ,"creacion"


        ]);

        foreach ($reporteVentas as $venta) {
            fputcsv($handle, [
                $venta['id'],
                $venta['empresa_id'],
                $venta['usuario']
                ,$venta['ltd_id']
                ,$venta['tracking_number']
                ,$venta['servicio_id']
                ,$venta['cia']
                ,$venta['created_at']
            ]);

        }
        fclose($handle);

        Reportes::create(array('cia' => $parametros['clienteIdCombo']
                            ,'ltd_id' => $parametros['ltdId']
                            ,'servicio_id'=>$parametros['servicio_id']
                            ,'fecha_ini' => $startTime = Carbon::parse( $parametros['fecha_ini'] )
                            ,'fecha_fin' => Carbon::parse( $parametros['fecha_fin'] )
                            ,'ruta_csv' => sprintf("public/%s",$nameCsv)
                            )
                        );
     
        try {

            $resultado = array();
            $mensaje = "ok";
            return $this->successResponse($reporteVentas, $mensaje);    

        } catch (\InvalidArgumentException $ex) {
            Log::debug($ex );
            $mensaje = $ex->getMessage();

        } catch (\ErrorException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." ErrorException");
            Log::debug(print_r($ex,true));
            
            $mensaje =$ex->getMessage();

        } catch (\HttpException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." HttpException");
            $resultado = $ex;
            $mensaje = $ex->getMessage();
        } catch (\Exception $e) {
            Log::info(__CLASS__." ".__FUNCTION__." Exception");
            Log::debug(print_r($e->getMessage(),true ));
           $mensaje = $e->getMessage();
        }
        Log::info(__CLASS__." ".__FUNCTION__." FINALIZANDO-----------------");
        return $this->sendError("Exception",$mensaje, "400");

    }
}
