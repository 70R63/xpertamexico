<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCotizadorRequest;
use App\Http\Requests\UpdateCotizadorRequest;
use Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

use App\Models\Cotizador;
use App\Models\Servicio;
use App\Models\Sucursal;
use App\Models\Cliente;

class CotizadorController extends Controller
{

    const INDEX_r = "cotizaciones.index";

    const DASH_v = "cotizaciones.dashboard";
    const CREAR_v = "cotizaciones.crear";
    const EDITAR_v = "cotizaciones.editar";
    const SHOW_v = "cotizaciones.show";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            Log::info(__CLASS__." ".__FUNCTION__);    
            
            $sucursal = Sucursal::pluck('contacto','id');

            $cliente = Cliente::pluck('contacto','id');

            return view(self::DASH_v 
                    ,compact( "sucursal", "cliente")
                );

        } catch (Exception $e) {
            Log::info(__CLASS__." ".__FUNCTION__." Exception");    
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        Log::info(__CLASS__." ".__FUNCTION__."CREATE INICIO-----------------");
        try {
            
            $objeto = $request->all();
            Log::info($objeto);
            $cliente = Cliente::findOrFail($request->get("cliente_id"));
            $sucursal = Sucursal::findOrFail($request->get("sucursal_id"));
            $servicio = Servicio::findOrFail($request->get("servicio_id"));

            $precio = $request->get("precio");
            $ltd_nombre = $request->get("ltd_nombre");
            $piezas = $request->get("piezas_guia");
  
            return view(self::CREAR_v
                , compact('cliente', 'sucursal', 'precio', 'piezas', 'ltd_nombre','objeto','servicio') 
            );

        } catch(\Illuminate\Database\QueryException $ex){ 
            Log::info(__CLASS__." ".__FUNCTION__." "."QueryException");
            Log::debug($ex->getMessage()); 
    
        } catch (Exception $e) {
            Log::info(__CLASS__." ".__FUNCTION__." "."Exception");
            Log::debug( $e->getMessage() );

        }

        return \Redirect::back()
                ->withErrors(array($ex->errorInfo[2]))
                ->withInput();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCotizadorRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCotizadorRequest $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cotizador  $cotizador
     * @return \Illuminate\Http\Response
     */
    public function show(StoreCotizadorRequest $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cotizador  $cotizador
     * @return \Illuminate\Http\Response
     */
    public function edit(Cotizador $cotizador)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCotizadorRequest  $request
     * @param  \App\Models\Cotizador  $cotizador
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCotizadorRequest $request, Cotizador $cotizador)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cotizador  $cotizador
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cotizador $cotizador)
    {
        //
    }
}
