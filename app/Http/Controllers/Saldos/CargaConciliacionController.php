<?php

namespace App\Http\Controllers\Saldos;

use App\Http\Requests\StoreCargaConciliacionRequest;
use App\Http\Requests\UpdateCargaConciliacionRequest;
use App\Models\CargaConciliacion;

class CargaConciliacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
