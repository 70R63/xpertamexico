<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\ApiController;
use Illuminate\Http\Request;
use App\Http\Requests\StoreReportesRequest;
use App\Http\Requests\UpdateReportesRequest;
use App\Models\API\Reportes_ventas;
use App\Models\API\Reportes;
use App\Models\API\Empresa;

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
        try {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
            Log::debug(print_r($request->all(),true));

            $reporteVentas = Reportes::select("reportes.*","empresas.nombre")
                            ->join('empresas', 'empresas.id', '=', 'reportes.cia')
                            ->get()->toArray()
                            //->toSql()
                            ;

            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
            //Log::debug(print_r($reporteVentas,true));
            

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
        try {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
            $parametros = $request->all();
            Log::debug(print_r($parametros,true));

            $empresa = Empresa::select("nombre")->where('id',$parametros['clienteIdCombo'])
                ->first()
                ;

            Log::info(print_r($empresa,true));

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

            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
            fputcsv($handle, [
                "ID GUIA",
                "EMPRESA_ID"
                ,"USUARIO"
                ,"LTD_ID"
                ,"TRACKINGNUMBER"
                ,"SERVICIO_ID"
                ,"EMPRESA"
                ,"CREACION"
                ,"FECHA ENVIO"
                ,"PESO BASCUAL"
                ,"LARGO"
                ,"ANCHO"
                ,"ALTO"
                ,"PESO DIMENSIONAL"
                ,"CIUDAD ORIGEN"
                ,"ESTADO ORIGEN"
                ,"CP ORIGEN"
                ,"CONTACTO REMITENTE"
                ,"CIUDAD DESTINO"
                ,"ESTADO DESTINO"
                ,"CP DESTINO"
                ,"CONTACTO DESTINO"
                ,"REFERENCIA"
                ,"NOTAS"
                ,"KGS EXTRA"
                ,"ZONA"
                ,"COSTO BASE"
                ,"COSTO KGS EXTRA"
                ,"COSTO A.E."
                ,"COSTO EXCESO DIMENSION Y/0 VOL IRREGULAR"
                ,"COSTO SERVICIO PREMIUM"
                ,"COSTO MULTIPIEZA"
                ,"COSTO SEGURO"
                ,"SUBTOTAL"
                ,"TOTAL"
                ,"ESTATUS RASTREO"


            ]);

            $contador = 0;
            foreach ($reporteVentas as $venta) {
                fputcsv($handle, [
                    $venta['id'],
                    $venta['empresa_id'],
                    $venta['usuario']
                    ,$venta['ltd_id']
                    ,sprintf("'%s'",trim($venta['tracking_number']))
                    ,$venta['servicio_id']
                    ,$venta['nombre']
                    ,$venta['created_at']
                    ,""
                    ,$venta['peso_bascula']
                    ,$venta['largo']
                    ,$venta['ancho']
                    ,$venta['alto']
                    ,$venta['peso_dimensional']
                    ,$venta['ciudad_origen']
                    ,$venta['entidad_federativa_origen']
                    ,$venta['cp_origen']
                    ,$venta['contacto_origen']
                    ,$venta['ciudad_origen']
                    ,$venta['entidad_federativa_destino']
                    ,$venta['cp_destino']
                    ,$venta['contacto_destino']
                    ,""
                    ,""
                    ,$venta['sobre_peso_kg']
                    ,$venta['zona']
                    ,$venta['costo_base']
                    ,$venta['costo_kg_extra']
                    ,""
                    ,""
                    ,""
                    ,""
                    ,$venta['seguro']
                    ,$venta['costo_base']+($venta['costo_kg_extra']*$venta['sobre_peso_kg'])
                    ,$venta['precio']
                    ,$venta['rastreo_nombre']

                ]);
                $contador++;
            }
            fclose($handle);
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
            Reportes::create(array('cia' => $parametros['clienteIdCombo']
                                ,'ltd_id' => $parametros['ltdId']
                                ,'servicio_id'=>$parametros['servicio_id']
                                ,'fecha_ini' => $startTime = Carbon::parse( $parametros['fecha_ini'] )
                                ,'fecha_fin' => Carbon::parse( $parametros['fecha_fin'] )
                                ,'ruta_csv' => sprintf("public/%s",$nameCsv)
                                ,'registros_cantidad' => $contador
                                )
                            );
     
        

            $resultado = array();
            $mensaje = "ok";
            header('Content-Type: text/csv');
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
