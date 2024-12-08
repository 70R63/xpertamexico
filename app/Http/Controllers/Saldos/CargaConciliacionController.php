<?php

namespace App\Http\Controllers\Saldos;

use App\Http\Controllers\Controller;

use App\Http\Requests\Saldos\StoreCargaConciliacionRequest;
use App\Http\Requests\Saldos\UpdateCargaConciliacionRequest;
use App\Models\CargaConciliacion;


//Utilerias
use Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use \Redirect;

//Exepciones
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use \Exception;
use Illuminate\Validation\ValidationException;

//Negocio
use App\Negocio\Saldos\CargaConciliacion as nCargaConciliacion;



class CargaConciliacionController extends Controller
{

    const INDEX_r = "cargaconciliacion.index";

    const DASH_v = "saldos.cargaconciliacion.dashboard";
    #const CREAR_v = "saldos.cargaconciliacion.crear";
    #const EDITAR_v = "saldos.cargaconciliacion.editar";
    const SHOW_v = "saldos.cargaconciliacion.show";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tabla = array();
        $ltds = array();
        $numeroDeSolicitud = Carbon::now()->timestamp;
        try {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ".$numeroDeSolicitud); 
           
            $cargaConciliacion = new nCargaConciliacion($numeroDeSolicitud);
            $cargaConciliacion->index();
                        
            $ltds = $cargaConciliacion->getLtds();
            $conciliacionesTabla = $cargaConciliacion->getConciliacionView();
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ".$numeroDeSolicitud);


            /*
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView("saldos.cargaconciliacion.dashboard.modal.reporte_cargaconciliacion"
                , array("tabla"=>$tabla)
            )->setPaper('a4', 'landscape');
        
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ".$numeroDeSolicitud);
            return $pdf->download('document.pdf');
            */    

            return view(self::DASH_v 
                    ,compact("ltds", "conciliacionesTabla")
                );


        } catch (ModelNotFoundException $e) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__); 
            $mensajeInterno=$e->getMessage();
            Log::debug(print_r($mensajeInterno,true));
            Log::info("ModelNotFoundException");       
            $mensaje = "ModelNotFoundException - Favor de buscar a tu administrador ";
        
        } catch (QueryException $e) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__); 
            $mensajeInterno=$e->getMessage();
            Log::debug(print_r($mensajeInterno,true));
            Log::info("QueryException");       
            $mensaje = "QueryException - Favor de buscar a tu administrador ";
        
        } catch (Exception $e) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__); 
            $mensaje=$e->getMessage();
            Log::debug(print_r($mensaje,true));
            Log::info("Error general ");       
        }

        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        return view(self::DASH_v 
                    ,compact("tabla", "ltds"))
                ->withErrors( $mensaje);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($facturaId)
    {
        $tabla = array();
        $ltds = array();
        $numeroDeSolicitud = Carbon::now()->timestamp;
        
        try {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ".$numeroDeSolicitud); 
           
            $cargaConciliacion = new nCargaConciliacion($numeroDeSolicitud);
            $cargaConciliacion->show($facturaId);
                        
            $ltds = $cargaConciliacion->getLtds();
            $conciliacionesTabla = $cargaConciliacion->getCargaConciliacions();
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ".$numeroDeSolicitud);


            
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView("saldos.cargaconciliacion.show.tabla_detalle"
                , array("conciliacionesTabla"=>$conciliacionesTabla)
            )->setPaper('a4', 'landscape');
        
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ".$numeroDeSolicitud);
            return $pdf->download('document.pdf');
        

            return view(self::DASH_v 
                    ,compact("ltds", "conciliacionesTabla")
                );


        } catch (ModelNotFoundException $e) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__); 
            $mensajeInterno=$e->getMessage();
            Log::debug(print_r($mensajeInterno,true));
            Log::info("ModelNotFoundException");       
            $mensaje = "ModelNotFoundException - Favor de buscar a tu administrador ";
        
        } catch (QueryException $e) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__); 
            $mensajeInterno=$e->getMessage();
            Log::debug(print_r($mensajeInterno,true));
            Log::info("QueryException");       
            $mensaje = "QueryException - Favor de buscar a tu administrador ";
        
        } catch (Exception $e) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__); 
            $mensaje=$e->getMessage();
            Log::debug(print_r($mensaje,true));
            Log::info("Error general ");       
        }

        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        return view(self::DASH_v 
                    ,compact("tabla", "ltds"))
                ->withErrors( $mensaje);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCargaConciliacionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCargaConciliacionRequest $request)
    {
        $data = $request->all();
        Log::debug(__CLASS__." ".__FUNCTION__." ".__LINE__." ".print_r($data,true));
        $numeroDeSolicitud = Carbon::now()->timestamp;
        try {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ".$numeroDeSolicitud); 

            $nCargaConciliacion = new nCargaConciliacion($numeroDeSolicitud);
            $nCargaConciliacion->store($data);


            $tmp = sprintf("El registro de la nueva DIRECCION '%s', fue exitoso",$request->get('nombre'));
            $notices = array($tmp);
  
            return Redirect::route(self::INDEX_r) -> withSuccess ($notices);

        } catch (ModelNotFoundException $e) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__); 
            $mensajeInterno=$e->getMessage();
            Log::debug(print_r($mensajeInterno,true));
            Log::info("ModelNotFoundException");       
            $mensaje = "ModelNotFoundException - Favor de buscar a tu administrador ";
        
        } catch (QueryException $e) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__); 
            $mensajeInterno=$e->getMessage();
            Log::debug(print_r($mensajeInterno,true));
            Log::info("QueryException");       
            $mensaje = "QueryException - Favor de buscar a tu administrador ";
        
        } catch (Exception $e) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__); 
            $mensaje=$e->getMessage();
            Log::debug(print_r($mensaje,true));
            Log::info("Error general ");       
        }

        return Redirect::back()
                ->withErrors(array($mensaje))
                ->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CargaConciliacion  $cargaConciliacion
     * @return \Illuminate\Http\Response
     */
    public function show($facturaId)
    {
        $tabla = array();
        $ltds = array();
        $conciliacionesTabla = array();
        $conciliacionView = array();
        
        $numeroDeSolicitud = Carbon::now()->timestamp;
        try {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." $numeroDeSolicitud - $facturaId"); 
           
            $nCargaConciliacion = new nCargaConciliacion($numeroDeSolicitud);
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ".$numeroDeSolicitud);
            $nCargaConciliacion->show($facturaId);
            
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ".$numeroDeSolicitud);
            $ltds = $nCargaConciliacion->getLtds();
            $conciliacionDetalleView = $nCargaConciliacion->getConciliacionDetalleView();
            $conciliacionView = $nCargaConciliacion->getConciliacionView()[0];


            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ".$numeroDeSolicitud);
 

            return view(self::SHOW_v 
                    ,compact("ltds", "conciliacionDetalleView","facturaId", "conciliacionView")
                )->withErrors( "validando show");

         } catch (ValidationException $ex) {

            $mensaje = $ex->getMessage();
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ".$numeroDeSolicitud);
            Log::debug(__CLASS__." ".__FUNCTION__." ".__LINE__." $numeroDeSolicitud $mensaje");
            

        } catch (ModelNotFoundException $e) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__); 
            $mensajeInterno=$e->getMessage();
            Log::debug(print_r($mensajeInterno,true));
            Log::info("ModelNotFoundException");       
            $mensaje = "ModelNotFoundException - Favor de buscar a tu administrador ";
        
        } catch (QueryException $e) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__); 
            $mensajeInterno=$e->getMessage();
            Log::debug(print_r($mensajeInterno,true));
            Log::info("QueryException");       
            $mensaje = "QueryException - Favor de buscar a tu administrador ";
        
        } catch (Exception $e) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__); 
            $mensaje=$e->getMessage();
            Log::debug(print_r($mensaje,true));
            Log::info("Error general ");       
        }

        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        return view(self::SHOW_v 
                    ,compact("tabla", "ltds", "conciliacionesTabla", "facturaId"))
                ->withErrors( $mensaje);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CargaConciliacion  $cargaConciliacion
     * @return \Illuminate\Http\Response
     */
    public function edit(CargaConciliacion $cargaConciliacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCargaConciliacionRequest  $request
     * @param  \App\Models\CargaConciliacion  $cargaConciliacion
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCargaConciliacionRequest $request, CargaConciliacion $cargaConciliacion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CargaConciliacion  $cargaConciliacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(CargaConciliacion $cargaConciliacion)
    {
        //
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CargaConciliacion  $cargaConciliacion
     * @return \Illuminate\Http\Response
     */
    public function descarga( $facturaId)
    {
        $tabla = array();
        $ltds = array();
        $conciliacionesTabla = array();
        $numeroDeSolicitud = Carbon::now()->timestamp;
        try {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ".$numeroDeSolicitud); 
           
            $cargaConciliacion = new nCargaConciliacion($numeroDeSolicitud);
            $cargaConciliacion->descarga($facturaId);
                        
            $ltds = $cargaConciliacion->getLtds();
            $conciliacionesTabla = $cargaConciliacion->getCargaConciliacions();
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ".$numeroDeSolicitud);


            
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView("saldos.cargaconciliacion.show.tabla_detalle"
                , array("conciliacionesTabla"=>$conciliacionesTabla)
            )->setPaper('a4', 'landscape');
        
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ".$numeroDeSolicitud);
            return $pdf->download('document.pdf');
        

            return view(self::DASH_v 
                    ,compact("ltds", "conciliacionesTabla")
                );


        } catch (ModelNotFoundException $e) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__); 
            $mensajeInterno=$e->getMessage();
            Log::debug(print_r($mensajeInterno,true));
            Log::info("ModelNotFoundException");       
            $mensaje = "ModelNotFoundException - Favor de buscar a tu administrador ";
        
        } catch (QueryException $e) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__); 
            $mensajeInterno=$e->getMessage();
            Log::debug(print_r($mensajeInterno,true));
            Log::info("QueryException");       
            $mensaje = "QueryException - Favor de buscar a tu administrador ";
        
        } catch (Exception $e) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__); 
            $mensaje=$e->getMessage();
            Log::debug(print_r($mensaje,true));
            Log::info("Error general ");       
        }

        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        return view(self::DASH_v 
                    ,compact("tabla", "ltds"))
                ->withErrors( $mensaje);
    }
}
