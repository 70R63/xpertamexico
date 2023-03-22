<?php

namespace App\Http\Controllers;

use App\Models\Guia;
use App\Models\EmpresaLtd;
use App\Models\Sucursal;
use App\Models\Cliente;
use App\Models\Cfg_ltd;
use App\Models\Servicio;
use App\Models\GuiasPaquete;


use App\Mail\GuiaCreada;

use App\Dto\EstafetaDTO;
use App\Dto\FedexDTO;
use App\Dto\RedpackDTO;

use App\Dto\Guia as GuiaDTO;

use App\Singlenton\Estafeta as sEstafeta;
use App\Singlenton\Fedex;
use App\Singlenton\Redpack;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Log;
use Mail;
use Config;
use Redirect;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

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
            $ltdActivo = Cfg_ltd::pluck("nombre","id");            
            $servicioPluck = Servicio::pluck("nombre","id");
            
            
            $tabla = Guia::select('guias.*','sucursals.cp', 'sucursals.ciudad','sucursals.contacto', 'clientes.cp as cp_d', 'clientes.ciudad as ciudad_d', 'clientes.contacto as contacto_d','empresas.nombre')
                        ->join('sucursals', 'sucursals.id', '=', 'guias.cia')
                        ->join('clientes', 'clientes.id', '=', 'guias.cia_d')
                        ->join('empresas', 'empresas.id', '=', 'sucursals.empresa_id')
                        //->offset(0)->limit(2000)
                        //->toSql();
                        //->where('guias.created_at', '>', now()->subDays(30)->endOfDay())
                        ->get()->toArray(); 
            
            Log::debug(__CLASS__." ".__FUNCTION__." Return View DASH_v ");
            return view(self::DASH_v 
                    ,compact("tabla", "ltdActivo", "servicioPluck")
                );
        } catch (\Exception $e) {
            Log::info(__CLASS__." ".__FUNCTION__);
            Log::debug(print_r($e->getMessage,true));
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
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." inicia ----------------------------");
        Log::debug(print_r($request->all(),true));
 
        if ($request->esManual != "NO") {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." iniciando no es manual ----------------------------");
            try{

                $cliente = new Cliente();
                $cliente->validaCliente($request);

                if ( !$cliente->getExiste() ) {
                    Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." Cliente no existe");
            
                    $cliente->insertSemiManual($request);
                }
                
                if ($request->esManual === "SI") {
                    Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." iniciando SI es manual ----------------------------");
                    $remitente = new Sucursal();
                    $remitente->existe($request);
                    
                    if ( !$remitente->getExiste() ) {
                        $remitente->insertParse($request);
                    }
                    $request['sucursal_id']=$remitente->getId();
                } 
                
                $request['cliente_id']=$cliente->getId();
                

            } catch(\Illuminate\Database\QueryException $ex){ 
                Log::info(__CLASS__." ".__FUNCTION__." "."QueryException");
                Log::debug(print_r($ex,true)); 
                $mensaje= array($ex->errorInfo[2]);

                return back()
                ->with('dangers',$mensaje)
                ->withInput();

            } catch (Exception $e) {
                Log::info(__CLASS__." ".__FUNCTION__." "."Exception");
                Log::debug( $e->getMessage() );
                $mensaje= array($e->getMessage());

                return back()
                ->with('dangers',$mensaje)
                ->withInput();
            }

            Log::info(__CLASS__." ".__FUNCTION__."finalizando es manual ----------------------------");
        }//FIN if$request->esManual != "NO"

        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." Case para mnsajeria");
        switch ($request['ltd_id']) {
            case Config('ltd.fedex.id'):
                Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ".Config('ltd.fedex.nombre') );
                return $this->fedex($request);
            break;
            case Config('ltd.estafeta.id'):
                Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ".Config('ltd.estafeta.nombre') );
                return $this->estafeta($request);
            break;
            case Config('ltd.redpack.id'):
                Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ".Config('ltd.redpack.nombre') );

                return $this->redpack($request);

                
            break;

            case Config('ltd.dhl.id'):
                Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." ".Config('ltd.dhl.nombre') );
                return back()
                ->with('dangers',array("Redpack en implementacion"))
                ->withInput();
                
            break;

            default:
                Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." Validar ");
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


    /**
     * Caso de uso pra estafeta 
     *
     * @param  \App\Models\Guia  $guia
     * @param  LTD_ID = 2
     * @return \Illuminate\Http\Response
     */
    private function estafeta($request){
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." estafeta iniciando ----------------------------");
        $mensaje = array();
        try {
            
            Log::debug($request);

            $requestInicial = $request->except(['_token']);

            Log::debug("Singlento Estafeta");

            $dto = new EstafetaDTO();
            $body = $dto->parser($requestInicial,"WEB");

            $sEstafeta = new sEstafeta(Config('ltd.estafeta.id'));
            Log::debug("sEstafeta -> envio()");
            $sEstafeta -> envio($body);
            $resultado = $sEstafeta->getResultado();

            Log::debug(print_r($sEstafeta->getTrackingNumber() ,true));

            $trackingNumbers = explode("|", $sEstafeta->getTrackingNumber());
            Log::debug(print_r($trackingNumbers ,true));            
            

            $carbon = Carbon::parse();
            $unique = crypt( (string)$carbon,'st');
            $carbon->settings(['toStringFormat' => 'Y-m-d-H-i-s']);
            $namePdf = sprintf("%s-%s.pdf",(string)$carbon,$unique);
            Storage::disk('public')->put($namePdf,base64_decode($sEstafeta->documento));

            $insert = GuiaDTO::estafeta($sEstafeta,$requestInicial,"WEB");

            $notices = array();
            $boolPrecio = true;
            foreach ($trackingNumbers as $key => $trackingNumber) {
                Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
                $insert['tracking_number'] = $trackingNumber;
                $insert['documento'] = $namePdf;
                Log::debug(print_r($insert ,true));   
                //dd("prueba");
                $id = Guia::create($insert)->id;
                $notices[] = sprintf("El registro de la solicitud se genero con exito con el ID %s ", $id);

                Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
                $guiaPaqueteInsert = GuiaDTO::validaPiezasPaquete($request, $key, $boolPrecio, $id);
                $boolPrecio = false;

                $idGuiaPaquite = GuiasPaquete::create($guiaPaqueteInsert)->id;
            }
            

            /*
            * Mail::to($request->email)
            *    ->cc(Config("mail.cc"))
            *    ->send(new GuiaCreada($request, $id));
            */
            
            
            Log::info(__CLASS__." ".__FUNCTION__." store Fin ----------------------------");
            Log::debug(__CLASS__." ".__FUNCTION__." INDEX_r");
            return \Redirect::route(self::INDEX_r) -> withSuccess ($notices);

         } catch (\Spatie\DataTransferObject\DataTransferObjectError $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." DataTransferObjectError");
            Log::debug(print_r($ex->getMessage(),true));
            
            $mensaje = array("DataTransferObjectError - Consulte a su proveedor");
            
        } catch (\GuzzleHttp\Exception\ConnectException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." ConnectException");
            Log::debug(print_r($ex->getMessage(),true));
            
            $mensaje = array("No se pudo establecer la conexion con el LTD");
       

        } catch (\GuzzleHttp\Exception\RequestException $re) {
            Log::info(__CLASS__." ".__FUNCTION__." RequestException INICIO ------------------");
            $response = ($re->getResponse());
            $responseContenido = json_decode($response->getBody()->getContents());    

            if (is_object($responseContenido)) {
                Log::info(__CLASS__." ".__FUNCTION__." is_object ");
                Log::debug(print_r($responseContenido,true));

                if (isset($responseContenido->code) && $responseContenido->code === 131) {
                    Log::debug(__CLASS__." ".__FUNCTION__." code 131 ");
                    $mensaje= array($responseContenido->description);

                } else {
                    Log::debug(__CLASS__." ".__FUNCTION__." code 131 else");
                    
                    $mensaje = array($responseContenido->error);                    
    
                }
                 
            } else{
               
                foreach ($responseContenido as $key => $value) {
                    $mensaje = array("desc$key"=> $value->description);                   
                }

            }
            Log::info(__CLASS__." ".__FUNCTION__." RequestException FIN ------------------");

        } catch (\GuzzleHttp\Exception\ClientException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." ClientException");
            $response = json_decode($ex->getResponse()->getBody());
            Log::debug(print_r($response,true));
            $mensaje = array($response->errors[0]->code);
            
        } catch (\GuzzleHttp\Exception\InvalidArgumentException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." InvalidArgumentException");
            Log::debug($ex->getBody());
            $mensaje = array("Se ha producido un error interno favor de contactar al proveedor");

        } catch(\Illuminate\Database\QueryException $ex){ 
            Log::info(__CLASS__." ".__FUNCTION__." "."QueryException");
            Log::debug(print_r($ex,true)); 
            Log::debug(print_r($insert,true)); 
            $mensaje= array($ex->errorInfo[2], "Tracking ".$insert['tracking_number'], "Contactar a su proveedor para el registro");

        } catch (Exception $e) {
            Log::info(__CLASS__." ".__FUNCTION__." "."Exception");
            Log::debug( $e->getMessage() );
            $mensaje= $e->getMessage();
        }

        Log::info(__CLASS__." ".__FUNCTION__." Finaliza ---------------------------- ");

        return back()
                ->with('dangers',$mensaje)
                ->withInput();
    }


    /**
     * Caso de uso pra fedex
     *
     * @param  \App\Models\Guia  $guia
     * @param  LTD_ID = 1
     * @return \Illuminate\Http\Response
     */
    private function fedex($request){

        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." iniciado ----------------------------");
        $mensaje = array();
        try {
            
            Log::debug($request);

            $requestInicial = $request->except(['_token']);

            $fedexDTO = new FedexDTO();
            $etiqueta = $fedexDTO->parser($request);
            
            Log::info(__CLASS__." ".__FUNCTION__." fedex->envio");
            $fedex = Fedex::getInstance(Config('ltd.fedex.id'));
            $fedex->envio( json_encode($etiqueta, JSON_UNESCAPED_UNICODE));

            

            Log::info(__CLASS__." ".__FUNCTION__." GuiaDTO");
            $guiaDTO = new GuiaDTO();
            $guiaDTO->parseoFedex($request,$fedex, "WEB");

            $insert = $guiaDTO->getInsert();

            $notices = array();
            $boolPrecio = true;
            foreach ($fedex->getDocumentos() as $key => $documento) {
                Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
                //dd( $documento->packageDocuments[0]->url );
                $insert['tracking_number'] = $documento->trackingNumber;
                $insert['documento'] = $documento->packageDocuments[0]->url;
                Log::debug(print_r($insert ,true));   
                
                Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." Guia::create");
                $id = Guia::create($insert)->id;
                $notices[] = sprintf("El registro de la solicitud se genero con exito con el ID %s ", $id);


                Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
                $guiaPaqueteInsert = GuiaDTO::validaPiezasPaquete($request, $key, $boolPrecio, $id);
                $boolPrecio = false;

                $idGuiaPaquite = GuiasPaquete::create($guiaPaqueteInsert)->id;
            }

            
            /*
            * Mail::to($request->email)
            *    ->cc(Config("mail.cc"))
            *    ->send(new GuiaCreada($request, $id));
            */
            
            Log::info(__CLASS__." ".__FUNCTION__."store Fin ----------------------------");
            Log::debug(__CLASS__." ".__FUNCTION__." INDEX_r");
            return \Redirect::route(self::INDEX_r) -> withSuccess ($notices);

         } catch (\Spatie\DataTransferObject\DataTransferObjectError $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." DataTransferObjectError");
            Log::debug(print_r($ex->getMessage(),true));
            
            $mensaje = array("DataTransferObjectError - Consulte a su proveedor");
            
        } catch (\GuzzleHttp\Exception\ConnectException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." ConnectException");
            Log::debug(print_r($ex->getMessage(),true));
            
            $mensaje = array("No se pudo establecer la conexion con el LTD");
       

        } catch (\GuzzleHttp\Exception\RequestException $re) {
            Log::info(__CLASS__." ".__FUNCTION__." RequestException INICIO ------------------");
            $response = ($re->getResponse());
            $responseContenido = json_decode($response->getBody()->getContents());    

            if (is_object($responseContenido)) {
                Log::info(__CLASS__." ".__FUNCTION__." is_object ");
                Log::debug(print_r($responseContenido,true));

                if (isset($responseContenido->code) && $responseContenido->code === 131) {
                    Log::debug(__CLASS__." ".__FUNCTION__." code 131 ");
                    $mensaje= array($responseContenido->description);

                } else {
                    Log::debug(__CLASS__." ".__FUNCTION__." code 131 else");
                    
                    $mensaje = array($responseContenido->errors[0]->message);
                }
                 
            } else{
               
                foreach ($responseContenido as $key => $value) {
                    $mensaje = array("desc$key"=> $value->description);                   
                }

            }
            Log::info(__CLASS__." ".__FUNCTION__." RequestException FIN ------------------");

        } catch (\GuzzleHttp\Exception\ClientException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." ClientException");
            $response = json_decode($ex->getResponse()->getBody());
            Log::debug(print_r($response,true));
            $mensaje = array($response->errors[0]->code);
            
        } catch (\GuzzleHttp\Exception\InvalidArgumentException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." InvalidArgumentException");
            Log::debug($ex->getBody());
            $mensaje = array("Se ha producido un error interno favor de contactar al proveedor");

        } catch(\Illuminate\Database\QueryException $ex){ 
            Log::info(__CLASS__." ".__FUNCTION__." "."QueryException");
            Log::debug(print_r($ex->getMessage(),true)); 
            Log::debug(print_r($guiaDTO->insert,true));
            $mensaje= array($ex->errorInfo[2], "Tracking ".$guiaDTO->insert['tracking_number'], "Contactar a su proveedor para el registro");

        } catch (Exception $e) {
            Log::info(__CLASS__." ".__FUNCTION__." "."Exception");
            Log::debug( $e->getMessage() );
            $mensaje= array($e->getMessage());
        }

        Log::info(__CLASS__." ".__FUNCTION__."FEDEX Finalizado ---------------------------- ");

        return back()
                ->with('dangers',$mensaje)
                ->withInput();
        
    }


    /**
     * Caso de uso pra redpack 
     *
     * @param  \App\Models\Guia  $guia
     * @param  LTD_ID = 3
     * @return \Illuminate\Http\Response
     */

    private function redpack($request){

        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." iniciado ----------------------------");
        $mensaje = array();
        try {

            $requestInicial = $request->except(['_token']);

            $empresa_id = auth()->user()->empresa_id;
            
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
          
            $redpackDTO = new RedpackDTO();
            $etiqueta = $redpackDTO->parser($request);
            if($redpackDTO->getRangoExedido()){
                return back()
                ->with('dangers',array("No se cuentan con Guias, valida con tu proveedor"))
                ->withInput();
            }

            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
            $redpack = new Redpack( $empresa_id );
            $redpack->documentation( $etiqueta );

            $notices = array();
            $boolPrecio = true;
            foreach ($redpack->getDocumento() as $key => $value) {
                Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." Documento");
                Log::info(print_r($value,true));

                $carbon = Carbon::parse();
                $carbon->settings(['toStringFormat' => 'Y-m-d-H-i-s']);
                $unique = crypt( (string)$carbon,'st');
                $namePdf = sprintf("%s-doc-%s-%s.pdf",(string)$carbon,$key,$unique);

                Storage::disk('public')->put($namePdf,base64_decode( $value->label ));

                $guiaDTO = new GuiaDTO();
                $guiaDTO->parseoRedpack($request,$redpack, "WEB", $namePdf);

                Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." Guia::create");

                $id = Guia::create($guiaDTO->getInsert())->id;
                $notices[] = sprintf("El registro de la solicitud se genero con exito con el ID %s ", $id);

                Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." GuiasPaquete::create");

                $precioUnitario = 0;
                if ($boolPrecio ){
                    $precioUnitario = $request['precio'];
                }
                $boolPrecio = false;

                if ( $request['piezas'] === 1 ) {
                    $guiaPaqueteInsert = array(
                        'peso' => $request['pesos'][$key]
                        ,'alto' => $request['altos'][$key]
                        ,'ancho' => $request['anchos'][$key]
                        ,'largo' => $request['largos'][$key]
                        ,'precio_unitario' => $precioUnitario
                        ,'guia_id' => $id
                    );    
                    
                } else {
                    if (count($request['pesos']) ===1) {
                        $key = 0;
                    }
                    $guiaPaqueteInsert = array(
                        'peso' => $request['pesos'][$key]
                        ,'alto' => $request['altos'][$key]
                        ,'ancho' => $request['anchos'][$key]
                        ,'largo' => $request['largos'][$key]
                        ,'precio_unitario' => $precioUnitario
                        ,'guia_id' => $id
                    );    

                }
                
                $idGuiaPaquite = GuiasPaquete::create($guiaPaqueteInsert)->id;

            }
           
            
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." store Fin ----------------------------");
            Log::debug(__CLASS__." ".__FUNCTION__." ".__LINE__." INDEX_r");
            return \Redirect::route(self::INDEX_r) -> withSuccess ($notices);
            

         } catch (\Spatie\DataTransferObject\DataTransferObjectError $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." DataTransferObjectError");
            Log::debug(print_r($ex->getMessage(),true));
            
            $mensaje = array("DataTransferObjectError - Consulte a su proveedor");
            
        } catch (\GuzzleHttp\Exception\ConnectException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." ConnectException");
            Log::debug(print_r($ex->getMessage(),true));
            
            $mensaje = array("No se pudo establecer la conexion con el LTD");
       

        } catch (\GuzzleHttp\Exception\RequestException $re) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." RequestException INICIO ------------------");
            $response = ($re->getResponse());
            Log::debug(print_r($re->getMessage(),true));
            $mensaje = array("RequestException - Consulte a su proveedor");
            
            Log::info(__CLASS__." ".__FUNCTION__." RequestException FIN ------------------");

        } catch (\GuzzleHttp\Exception\ClientException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." ClientException");
            $response = json_decode($ex->getResponse()->getBody());
            Log::debug(print_r($response,true));
            $mensaje = array($response->errors[0]->code);
            
        } catch (\GuzzleHttp\Exception\InvalidArgumentException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." InvalidArgumentException");
            Log::debug($ex->getBody());
            $mensaje = array("Se ha producido un error interno favor de contactar al proveedor");

        } catch(\Illuminate\Database\QueryException $ex){ 
            Log::info(__CLASS__." ".__FUNCTION__." "."QueryException");
            Log::debug(print_r($ex->getMessage(),true)); 
            Log::debug(print_r($guiaDTO->insert,true));
            $mensaje= array($ex->errorInfo[2], "Tracking ".$guiaDTO->insert['tracking_number'], "Contactar a su proveedor para el registro");

        } catch (Exception $e) {
            Log::info(__CLASS__." ".__FUNCTION__." "."Exception");
            Log::debug( $e->getMessage() );
            $mensaje= array($e->getMessage());
        }

        Log::info(__CLASS__." ".__FUNCTION__."FEDEX Finalizado ---------------------------- ");

        return back()
                ->with('dangers',$mensaje)
                ->withInput();
        
    }


}
