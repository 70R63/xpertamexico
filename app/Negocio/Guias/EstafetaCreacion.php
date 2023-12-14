<?php 

namespace App\Negocio\Guias;

use Log;
use Illuminate\Validation\ValidationException;


use App\Singlenton\EstafetaDev;

//models
use App\Models\LtdTipoServicio;
use App\Models\Cliente;
use App\Models\Sucursal;
use App\Models\LtdCobertura;

use App\Negocio\Guias\Cotizacion as nCotizacion;
use App\Negocio\Saldos\Saldos as nSaldos;



Class EstafetaCreacion {

	private $response;
	
	/**
     * Se busca obtener las tarifas de FEDEX basado en el KG .
     * 
     * @author Javier Hernandez
     * @copyright 2022-2023 XpertaMexico
     * @package App\Negocio\Guias
     * @api
     * 
     * @version 1.0.0
     * 
     * @since 1.0.0 Primera version de la funcion cotizacionDEV
     * 
     * @throws
     *
     * @param  Illuminate\Http\Request  $request Recibe la paticion del cliente
     * 
     * @var array $data Se convierte el Json de la peticion a array
     * 
     * @return json Objeto con la respuesta de exito o fallo 
     */

    public function parseoApi(array $data){

    	Log::debug(__CLASS__." ".__FUNCTION__." "." sEstafeta -> envio()");
    	Log::debug($data);
    	$data['empresa_id']=308;
    	$data['esManual']="API" ;

    	Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        $data = $this->validaCliente($data);

        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        $data = $this->validaSucursal($data);

    	Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
    	$data = $this->ltdTipoServicio($data);

    	Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        $data = $this->validaLtdCobertura($data);

    	$data = $this->tarifas($data);

    	Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
    	$data = $this->cotizacion($data);

    	Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
    	$this->saldo($data);

    	Log::debug($data);
        
        $trackingNumber = "";
        $systemInformation = array("id"=>"AP01",
            "name"=>"AP01",
            "version"=>"1.10.20");
        $data['systemInformation']= $systemInformation;
        $data['labelDefinition']['serviceConfiguration']['quantityOfLabels'] = 1;

        //$data['labelDefinition']['itemDescription']['weight'] = 1;
        Log::debug("Se intancia el Singlento Estafeta");
        
        $sEstafeta = new EstafetaDev(2, 2, "API");

        Log::debug(__CLASS__." ".__FUNCTION__." "." sEstafeta -> envio()");
        Log::debug( json_encode($data) );

        $sEstafeta -> envio($data);

        
        $this->response = $sEstafeta->getResultado();
        $trackingNumber = $sEstafeta->getTrackingNumber();
        $this->notices[] ="Exito";
        $this->notices[]= sprintf("El registro de la solicitud se genero con exito con el ID xxxx");
        

    }
    

    /**
     * Busca los valor de LTD servicio por empresaId y servicioID .
     * 
     * @author Javier Hernandez
     * @copyright 2022-2023 XpertaMexico
     * @package App\Negocio\Guias
     * @api
     * 
     * @version 1.0.0
     * 
     * @since 1.0.0 Primera version de la funcion ltdTipoServicio
     * 
     * @throws
     *
     * @param array  $data Array conta la infroamcion del flujo
     * 
     * @var array $data Se convierte el Json de la peticion a array
     * 
     * @return json Objeto con la respuesta de exito o fallo 
     */

    public function ltdTipoServicio(array $data){

    	Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);

    	$ltdTipoServicio = LtdTipoServicio::where("empresa_id", $data['empresa_id'])
    					->where("ltd_id", $data['ltd_id'])
    					->where("service_id",$data['servicio_id'])
    					->firstOrFail()
    					->toArray()
    					;

    	Log::debug(print_r($ltdTipoServicio,true));

    	$identification = array(
            "suscriberId"=>$ltdTipoServicio['client_id']
            ,"customerNumber"=>$ltdTipoServicio['customer_number']
        );

        $data['identification']=$identification;
        $data['labelDefinition']['serviceConfiguration']['salesOrganization']=$ltdTipoServicio['sales_organization']; 
        
        $data['labelDefinition']['serviceConfiguration']['serviceTypeId'] = $ltdTipoServicio['service_id_ltd'];

    	Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
    	return $data;
    }


    /**
     * Revisa si hay terifas para el licente usando Flat.
     * 
     * @author Javier Hernandez
     * @copyright 2022-2023 XpertaMexico
     * @package App\Negocio\Guias
     * @api
     * 
     * @version 1.0.0
     * 
     * @since 1.0.0 Primera version de la funcion tarifas
     * 
     * @throws
     *
     * @param array  $data Array conta la infroamcion del flujo
     * 
     * @var array $data Se convierte el Json de la peticion a array
     * 
     * @return json Objeto con la respuesta de exito o fallo 
     */

    public function tarifas(array $data){
    	Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);

    	$nCotizacion = new nCotizacion();

    	$nCotizacion->base($data, $data['ltd_id'],$data['esManual']);

    	Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
    	$cotizacion = $nCotizacion->getTabla();

    	if (count($cotizacion) <1) {
    		throw ValidationException::withMessages(array("No cuenta con tarifas."));
    	}
    	$data = array_merge($data, $cotizacion[0]);

    	Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
    	return $data;
    }


    /**
     * Valida la existencia del cliente en caso contrario parsea el request
     * 
     * @author Javier Hernandez
     * @copyright 2022-2023 XpertaMexico
     * @package App\Negocio\Guias
     * @api
     * 
     * @version 1.0.0
     * 
     * @since 1.0.0 Primera version de la funcion validaCliente
     * 
     * @throws
     *
     * @param array $data Informacion general de la petricion
     * 
     * @var int 
     * 
     * 
     * @return $data Se agra informacion segun la necesidad
     */

    public function validaCliente($data){
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
         
        $labelDefinition = $data['labelDefinition'];
        $locations = $labelDefinition['location'];

        $destination = $locations['destination'];
        $homeAddress = $destination['homeAddress'];

       	$contacto = $homeAddress['contact'];
       	$direccion = $homeAddress['address'];

       	$data['nombre_d'] = $contacto['contactName'];
       	$data['cp_d']   = $direccion['zipCode'];
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        $cliente = new Cliente();
        $cliente->validaCliente($data);

        if ( !$cliente->getExiste() ) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." Cliente no existe");

            $data['contacto_d'] = $contacto['contactName'];
            $data['direccion_d']= $direccion['roadName'];
            $data['direccion2_d']=$direccion['addressReference'];
            $data['colonia_d']  = $direccion['settlementName'];
            $data['ciudad_d']   = "";
            $data['entidad_federativa_d']="";
            $data['celular_d']  = $contacto['cellPhone'];
            $data['telefono_d'] = $contacto['cellPhone'];;
            $data['no_ext_d']   = $direccion['externalNum'];
            $data['no_int_d']   = '';
            
    
            $cliente->insertSemiManual($data);

        }
        $data['cliente_id']=$cliente->getId();

        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        return $data;
        
    }

    /**
     * Valida la existencia del remitente (sucursal)
     * 
     * @author Javier Hernandez
     * @copyright 2022-2023 XpertaMexico
     * @package App\Negocio\Guias
     * @api
     * 
     * @version 1.0.0
     * 
     * @since 1.0.0 Primera version de la funcion validaSucursal
     * 
     * @throws
     *
     * @param array $data Informacion general de la petricion
     * 
     * @var int 
     * 
     * 
     * @return $data Se agra informacion segun la necesidad
     */

    public function validaSucursal($data){
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);


        $labelDefinition = $data['labelDefinition'];
        $locations = $labelDefinition['location'];

        $origen = $locations['origin'];
        
       	$contacto = $origen['contact'];
       	$direccion = $origen['address'];

       	$data['nombre'] = $contacto['contactName'];
       	$data['cp']   = $direccion['zipCode'];

        $remitente = new Sucursal();
        $remitente->existe($data);
        
        if ( !$remitente->getExiste() ) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);

            $data['contacto'] = $contacto['contactName'];
            $data['direccion']= $direccion['roadName'];
            $data['direccion2']=$direccion['addressReference'];
            $data['colonia']  = $direccion['settlementName'];
            $data['ciudad']   = "";
            $data['entidad_federativa']="";
            $data['celular']  = $contacto['cellPhone'];
            $data['telefono'] = $contacto['cellPhone'];;
            $data['no_ext']   = $direccion['externalNum'];
            $data['no_int']   = '';
            

            $remitente->insertParse($data);
        }
        $data['sucursal_id']=$remitente->getId();
        $data['sucursal'] = $data['sucursal_id'];

        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);

        return $data;
    }


    /**
     * Valida la existencia del remitente (sucursal)
     * 
     * @author Javier Hernandez
     * @copyright 2022-2023 XpertaMexico
     * @package App\Negocio\Guias
     * @api
     * 
     * @version 1.0.0
     * 
     * @since 1.0.0 Primera version de la funcion validaSucursal
     * 
     * @throws
     *
     * @param array $data Informacion general de la petricion
     * 
     * @var int 
     * 
     * 
     * @return $data Se agra informacion segun la necesidad
     */

    public function cotizacion($data){
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);

        
        $data['piezas']=1;
        $data['costo_base']=$data['costo'];

        $dimensiones = $data['labelDefinition']['itemDescription'];

        $serviceConfiguration = $data['labelDefinition']['serviceConfiguration'];

        $data['costo_seguro']=0;
        if($serviceConfiguration['isInsurance']){
        	$data['valor_declarado'] = $serviceConfiguration['insurance']['declaredValue'];

        	$data['costo_seguro'] = ($data['valor_declarado']*$data['seguro'])/100;
        }
        
        $data['peso'] = $dimensiones['weight'];
        $data['alto'] = $dimensiones['height'];
        $data['ancho'] = $dimensiones['width'];
        $data['largo'] = $dimensiones['length'];


        $data['peso_bascula'] = $data['peso'];
        $data['peso_dimensional'] = ($data['alto']*$data['ancho']*$data['largo'])/5000;
        

        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        $data['pesoFacturado'] = ($data['peso_bascula'] > $data['peso_dimensional']) ? ceil($data['peso_bascula']) : ceil($data['peso_dimensional']) ;


        //caclulo extendida 
        $data['costo_extendida'] = 0;
        if ($data['extendida'] === 'SI') {
            $data['costo_extendida']=$data['exceso_dimension'];
        }


    	$data['costo_kg_extra']=0;
        $data['sobre_peso_kg']=0;
    	if ($data['pesoFacturado'] >$data['kg_fin']) {
    		$data['sobre_peso_kg'] = $data['pesoFacturado']-$data['kg_fin'];
    		$data['costo_kg_extra'] = $data['sobre_peso_kg']*$data['kg_extra'];
    	}


        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        $data['subPrecio'] = $data['costo_base']+$data['costo_kg_extra']+$data['costo_seguro']+$data['costo_extendida'];

        


        $data['precio']=$data['subPrecio']*1.16;

        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        return $data;
    }


    /**
     * SE valida si el CP y PAquereia tiene Cobertura Extendida 
     * 
     * @author Javier Hernandez
     * @copyright 2022-2023 XpertaMexico
     * @package App\Negocio\Guias
     * @api
     * 
     * @version 1.0.0
     * 
     * @since 1.0.0 Primera version de la funcion insertDTO
     * 
     * @throws
     *
     * @param array $data Informacion general de la peticion
     * 
     * @var $ltdCobertura Array que tendra los datos de la cobertura
     * 
     * 
     * @return $data Se agra informacion segun la necesidad
     */

    public function validaLtdCobertura($data){
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        $ltdCobertura = LtdCobertura::where("ltd_id", $data['ltd_id'])
                    ->where("cp",$data['cp_d'])
                    ->get()->toArray();

        Log::debug( print_r($ltdCobertura,true) );

        if ( ! (count($ltdCobertura) ===1) ) {
            throw ValidationException::withMessages(array("Inconsistencia en la Cobertura, Favor de validar con el Administrador"));
        }
        $data['extendida']=$ltdCobertura[0]['extendida'];
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);

        return $data;
    }



    /**
     * Valida la existencia del remitente (sucursal)
     * 
     * @author Javier Hernandez
     * @copyright 2022-2023 XpertaMexico
     * @package App\Negocio\Guias
     * 
     * @version 1.0.0
     * 
     * @since 1.0.0 Primera version de la funcion saldo
     * 
     * @throws
     *
     * @param array $data Informacion general de la peticion
     * 
     * @var int 
     * @var float $monto
     * 
     * 
     * @return $data Se agra informacion segun la necesidad
     */

    public function saldo($data){
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        $saldo = new nSaldos();
        $monto = $saldo-> porEmpresa($data['empresa_id']);

        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        $saldo->validaSaldo($monto);

        $saldo->menosPrecio($data["sucursal_id"], $data["precio"]);
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
    }


    public function getResponse(){
        return $this->response;
    }

    public function getNotices(){
        return $this->notices;
    }


}