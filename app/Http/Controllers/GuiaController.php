<?php

namespace App\Http\Controllers;

use App\Models\Guia;
use App\Models\EmpresaLtd;
use App\Mail\GuiaCreada;
use App\Dto\Estafeta;
use App\Dto\Guia as GuiaDTO;
use GuzzleHttp\Client;

use Illuminate\Http\Request;
use Log;
use Mail;

class GuiaController extends Controller
{

    const INDEX_r = "guia.index";

    const DASH_v = "guia.dashboard";
    const CREAR_v = "guia.crear";
    const EDITAR_v = "guia.editar";
    const SHOW_v = "guia.show";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            Log::info(__CLASS__." ".__FUNCTION__); 
            $ltdActivo = EmpresaLtd::LtdEmpresa()->pluck("nombre","ltd_id");
            
            $tabla = Guia::get(); 
            
            return view('guia.dashboard' 
                    ,compact("tabla", "ltdActivo")
                );
        } catch (Exception $e) {
            Log::info(__CLASS__." ".__FUNCTION__);
            Log::info("Error general ");       
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
            $ltdActivo = EmpresaLtd::LtdEmpresa()
                    ->where('empresa_id', auth()->user()->empresa_id)
                    ->pluck("nombre","id");
            
            $tabla = array();
            return view('guia.crear' 
                    ,compact("tabla", "ltdActivo")
                );
        } catch (Exception $e) {
            Log::info(__CLASS__." ".__FUNCTION__);
            Log::info("Error general ");       
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info(__CLASS__." ".__FUNCTION__);
        try {
            
            Log::debug($request);
            /*
            $guia = new Estafeta();
            $guia->parser($request);
            $result = $guia -> init(); 
            Log::info($result);
            */
            $guiaDTO = new GuiaDTO();
            $guiaDTO->parser($request);

            $id = Guia::create($guiaDTO->insert)->id;
            Mail::to($request->email)
                ->cc(\Config("mail.cc"))
                ->send(new GuiaCreada($request, $id));
           
            $tmp = sprintf("El registro de la guia '%s', fue exitoso",$id);
            $notices = array($tmp);
  
            return \Redirect::route(self::INDEX_r) -> withSuccess ($notices);

        } catch(\Illuminate\Database\QueryException $ex){ 
            Log::info(__CLASS__." ".__FUNCTION__." "."QueryException");
            Log::debug($ex->getMessage()); 
            return \Redirect::back()
                ->withErrors(array($ex->errorInfo[2]))
                ->withInput();

        } catch (Exception $e) {
            Log::info(__CLASS__." ".__FUNCTION__." "."Exception");
            Log::debug( $e->getMessage() );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Guia  $guia
     * @return \Illuminate\Http\Response
     */
    public function show(Guia $guia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Guia  $guia
     * @return \Illuminate\Http\Response
     */
    public function edit(Guia $guia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Guia  $guia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Guia $guia)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Guia  $guia
     * @return \Illuminate\Http\Response
     */
    public function destroy(Guia $guia)
    {
        //
    }
}
