<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDireccionRequest;
use App\Http\Requests\UpdateDireccionRequest;
use App\Models\Direccion;
use App\Models\Ltd;
use App\Models\Servicio;

use Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DireccionController extends Controller
{
     const INDEX_r = "direcciones.index";

    const DASH_v = "direcciones.dashboard";
    const CREAR_v = "direcciones.crear";
    const EDITAR_v = "direcciones.editar";
    const SHOW_v = "direcciones.show";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            Log::info(__CLASS__." ".__FUNCTION__);    
            $tabla = Direccion::get();

            $pluckLtd = Ltd::where('estatus',1)
                                ->pluck('nombre','id');

            $pluckServicio = Servicio::where('estatus',1)
                    ->pluck('nombre','id');

            $registros = $tabla->count();

            return view(self::DASH_v 
                    ,compact("tabla", "pluckLtd", "pluckServicio", "registros")
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
        try {
            Log::info(__CLASS__." ".__FUNCTION__);    
            $tabla = array();

            $pluckLtd = Ltd::where('estatus',1)
                                ->pluck('nombre','id');

            $pluckServicio = Servicio::where('estatus',1)
                                ->pluck('nombre','id');
            return view(self::CREAR_v 
                    ,compact("tabla","pluckLtd", "pluckServicio")
                );
        } catch (Exception $e) {
            Log::info(__CLASS__." ".__FUNCTION__);
            Log::info("Error general ");       
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDireccionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDireccionRequest $request)
    {
        Log::info(__CLASS__." ".__FUNCTION__);
        $mensaje = "";
        try {
            
            Direccion::create($request->except('_token'));

            $tmp = sprintf("El registro de la nueva DIRECCION '%s', fue exitoso",$request->get('nombre'));
            $notices = array($tmp);
  
            return \Redirect::route(self::INDEX_r) -> withSuccess ($notices);

        } catch(\Illuminate\Database\QueryException $e){ 
            Log::info(__CLASS__." ".__FUNCTION__." "."QueryException");
            Log::debug($e->getMessage()); 
            $mensaje= $e->getMessage();
        } catch (Exception $e) {
            Log::info(__CLASS__." ".__FUNCTION__." "."Exception");
            Log::debug( $e->getMessage() );
            $mensaje= $e->getMessage();
        }

        return \Redirect::back()
                ->withErrors(array($mensaje))
                ->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Direccion  $direccion
     * @return \Illuminate\Http\Response
     */
    public function show(Direccion $direccion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Direccion  $direccion
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $mensaje = "";
        try {
            Log::debug($id);
            Log::info(__CLASS__." ".__FUNCTION__."");
            $objeto = Direccion::findOrFail($id);
               
            Log::debug($objeto);
            return view(self::EDITAR_v
                , compact('objeto') 
            );
       
        } catch (ModelNotFoundException $e) {
            Log::info(__CLASS__." ".__FUNCTION__." ModelNotFoundException");
            $mensaje = $e->getMessage();
        } catch (Exception $e) {
            Log::info(__CLASS__." ".__FUNCTION__." "."Exception");
            Log::debug( $e->getMessage() );
            $mensaje = $e->getMessage();    
        }

        return \Redirect::back()
                ->withErrors(array($mensaje))
                ->withInput();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDireccionRequest  $request
     * @param  \App\Models\Direccion  $direccion
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDireccionRequest $request, Direccion $direccion)
    {
        Log::info(__CLASS__." ".__FUNCTION__);
        $mensaje = "";
        try {
            
            $direccion->fill($request->post())->save();
  
            $tmp = sprintf("Actualizacion del id '%s', fue exitoso",$direccion->id);
            $notices = array($tmp);

            return \Redirect::route(self::INDEX_r) -> withSuccess ($notices);

        } catch(\Illuminate\Database\QueryException $e){ 
            Log::info(__CLASS__." ".__FUNCTION__." "."QueryException");
            Log::debug($e->getMessage()); 
            $mensaje =  $e->getMessage();
        } catch (Exception $e) {
            Log::info(__CLASS__." ".__FUNCTION__." "."Exception");
            Log::debug( $e->getMessage() );
            $mensaje =  $ex->getMessage();
        }

        return \Redirect::back()
                ->withErrors(array($mensaje))
                ->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Direccion  $direccion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Direccion $direccion)
    {
        Log::info(__CLASS__." ".__FUNCTION__);
        $mensaje = "";
        try {
            Log::debug(print_r($direccion,true));
            Log::info("Registro a Eliminar ". $direccion->id);
            $tmp = sprintf("El Registro de la Direccion '%s', fue eliminado exitosamente",$direccion->id);
            $notices = array($tmp);

            $direccion->estatus = 0;
            $direccion->save();
  
            return \Redirect::route(self::INDEX_r) -> withSuccess ($notices);

        } catch(\Illuminate\Database\QueryException $e){ 
            Log::info(__CLASS__." ".__FUNCTION__." "."QueryException");
            Log::debug($e->getMessage()); 
            $mensaje = $e->getMessage();

        } catch (Exception $e) {
            Log::info(__CLASS__." ".__FUNCTION__." "."Exception");
            Log::debug( $e->getMessage() );
            $mensaje = $e->getMessage();
        }

        return \Redirect::back()
                ->withErrors(array($mensaje))
                ->withInput();
    }
}
