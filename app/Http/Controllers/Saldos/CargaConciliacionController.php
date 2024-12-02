<?php

namespace App\Http\Controllers\Saldos;

use App\Http\Controllers\Controller;

use App\Http\Requests\StoreCargaConciliacionRequest;
use App\Http\Requests\UpdateCargaConciliacionRequest;
use App\Models\CargaConciliacion;


//Utilerias
use Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

//Exepciones
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use \Exception;

//Negocio
use App\Negocio\Saldos\CargaConciliacion as nCargaConciliacion;



class CargaConciliacionController extends Controller
{

    const INDEX_r = "cargaconciliacion.index";

    const DASH_v = "saldos.cargaconciliacion.dashboard";
    #const CREAR_v = "saldos.cargaconciliacion.crear";
    #const EDITAR_v = "saldos.editar";
    #const SHOW_v = "saldos.show";
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
            //$nExterna->tabla();
            //$tabla = $nExterna->getTabla();
                        
            $ltds = $cargaConciliacion->getLtds();
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
                    ,compact("ltds", "tabla")
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
        
        } catch (\Exception $e) {
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCargaConciliacionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCargaConciliacionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CargaConciliacion  $cargaConciliacion
     * @return \Illuminate\Http\Response
     */
    public function show(CargaConciliacion $cargaConciliacion)
    {
        //
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
}
