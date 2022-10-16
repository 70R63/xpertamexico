<?php

namespace App\Http\Controllers;

use App\Models\Guia;
use App\Models\EmpresaLtd;
use App\Models\Sucursal;
use App\Models\Cliente;
use App\Models\Ltd;

use App\Mail\GuiaCreada;

use App\Dto\Estafeta;
use App\Dto\Guia as GuiaDTO;
use App\Dto\FedexDTO;

use App\Singlenton\Fedex;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Log;
use Mail;
use Config;

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
            $ltdActivo = Ltd::pluck("nombre","id");
            $cliente = Cliente::pluck("nombre","id");
            $sucursal = Sucursal::pluck("nombre","id");
            $tabla = Guia::get(); 
            
            Log::debug(__CLASS__." ".__FUNCTION__." Return View DASH_v ");
            return view(self::DASH_v 
                    ,compact("tabla", "ltdActivo","cliente","sucursal")
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
            $ltdActivo = EmpresaLtd::Ltds()
                    ->pluck("nombre","id");
            
            return view(self::CREAR_v 
                    ,compact("ltdActivo")
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
        $mensaje = "Error General";
        try {
            
            Log::debug($request);
            
            $fedex = Fedex::getInstance($request['ltd_id']);

            $fedexDTO = new FedexDTO();
            $etiqueta = $fedexDTO->parser($request);
            
            $fedex->envio(json_encode($etiqueta));

            $guiaDTO = new GuiaDTO();
            $guiaDTO->parser($request);

            Log::info(__CLASS__." ".__FUNCTION__." Guia::create");
            $id = Guia::create($guiaDTO->insert)->id;
            Mail::to($request->email)
                ->cc(Config("mail.cc"))
                ->send(new GuiaCreada($request, $id));
           
            $tmp = sprintf("El registro de la guia con ID %d fue exitoso, la guia sera enviada al correo '%s' ",$id,$request->email);
            $notices = array($tmp);
            
            Log::debug(__CLASS__." ".__FUNCTION__." INDEX_r");
            return \Redirect::route(self::INDEX_r) -> withSuccess ($notices);

        } catch (\GuzzleHttp\Exception\ClientException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." ClientException");
            $response = json_decode($ex->getResponse()->getBody());
            Log::debug(print_r($response,true));
            $mensaje = $response->errors[0]->code;
            
        } catch (\GuzzleHttp\Exception\InvalidArgumentException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." InvalidArgumentException");
            Log::debug($ex->getBody());
            $mensaje = "Se ha producido un error interno favor de contactar al proveedor";

        } catch(\Illuminate\Database\QueryException $ex){ 
            Log::info(__CLASS__." ".__FUNCTION__." "."QueryException");
            Log::debug($ex->getMessage()); 
            $mensaje= $ex->errorInfo[2];

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
