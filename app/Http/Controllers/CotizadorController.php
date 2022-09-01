<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCotizadorRequest;
use App\Http\Requests\UpdateCotizadorRequest;
use App\Models\Cotizador;

use App\Models\Ltd;
use App\Models\Servicio;
use Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
            
            $pluckLtd = Ltd::where('estatus',1)
                                ->pluck('nombre','id');

            $pluckServicio = Servicio::where('estatus',1)
                    ->pluck('nombre','id');

            return view(self::DASH_v 
                    ,compact( "pluckLtd", "pluckServicio")
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCotizadorRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCotizadorRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cotizador  $cotizador
     * @return \Illuminate\Http\Response
     */
    public function show(Cotizador $cotizador)
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
