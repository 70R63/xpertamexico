<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRastreosRequest;
use App\Http\Requests\UpdateRastreosRequest;
use App\Models\Rastreos;
use App\Models\Guia;
use App\Models\Rastreo_peticion;


//Generales 
use Log;

class RastreosController extends Controller
{

    const DASH_v = "rastreos.dashboard";
    const CREAR_v = "rastreos.crear";
    const EDITAR_v = "rastreos.editar";
    const SHOW_v = "rastreos.show";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            Log::info(__CLASS__." ".__FUNCTION__." INICIANDO-----------------"); 
            $rastreoPeticion = Rastreo_peticion::latest()->first();
            Log::debug(print_r($rastreoPeticion->peticion_fin,true));

            Log::debug(__CLASS__." ".__FUNCTION__." FINALIZANDO----------------- ");
            return view(self::DASH_v 
                    ,compact("rastreoPeticion") 
                );
        } catch (\Exception $e) {
            Log::info(__CLASS__." ".__FUNCTION__." Exception FINALIZANDO-----------------");
            Log::debug(print_r($e->getMessage(),true));
            
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
     * @param  \App\Http\Requests\StoreRastreosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRastreosRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rastreos  $rastreos
     * @return \Illuminate\Http\Response
     */
    public function show(Rastreos $rastreos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Rastreos  $rastreos
     * @return \Illuminate\Http\Response
     */
    public function edit(Rastreos $rastreos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRastreosRequest  $request
     * @param  \App\Models\Rastreos  $rastreos
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRastreosRequest $request, Rastreos $rastreos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rastreos  $rastreos
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rastreos $rastreos)
    {
        //
    }
}
