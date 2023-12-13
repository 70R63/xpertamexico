<?php 

namespace App\Negocio\Guias;

use Log;

use App\Singlenton\EstafetaDev;

//models
use App\Models\LtdTipoServicio;

use App\Negocio\Guias\Cotizacion as nCotizacion;

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
    	Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
    	$data = $this->ltdTipoServicio($data);

    	Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        $data = $this->validaCliente($data);

        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        $data = $this->validaSucursal($data);

    	$data = $this->cotizacion($data);

        
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

    	$ltdTipoServicio = LtdTipoServicio::where("empresa_id", 308)
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

    public function cotizacion(array $data){
    	Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);

    	$nCotizacion = new nCotizacion();

    	Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);

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
       	$direccion = $homeAddress['address'];;
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        $cliente = new Cliente();
        $cliente->validaCliente($data);

        if ( !$cliente->getExiste() ) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." Cliente no existe");

            $data['contacto_d'] = $contacto['contactName'];
            $data['direccion_d']= $homeAddress['address'][];
            $data['direccion2_d']="";
            $data['colonia_d']  = $this->clienteJson['address']['city'];
            $data['ciudad_d']   = $this->clienteJson['address']['city'];
            $data['entidad_federativa_d']=$this->clienteJson['address']['stateOrProvinceCode'];
            $data['celular_d']  = $this->clienteJson['contact']['phoneNumber'];
            $data['telefono_d'] = $this->clienteJson['contact']['phoneNumber'];;
            $data['no_ext_d']   = '';
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


        $data['nombre']= trim($sucursalJson['contact']['personName']);
        $data['contacto'] = $data['nombre'];
        $direcciones = $this->sucursalJson['address']['streetLines'];

        $direccion = $direcciones[0];

        $remitente = new Sucursal();
        $remitente->existe($data);
        
        if ( !$remitente->getExiste() ) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);

            $data['direccion']= $direccion;
            $data['direccion2']="";
            $data['colonia']  = $this->sucursalJson['address']['city'];
            $data['ciudad']   = $this->sucursalJson['address']['city'];
            $data['entidad_federativa']= $this->sucursalJson['address']['stateOrProvinceCode'] ;
            $data['celular']  = $sucursalJson['contact']['phoneNumber'];
            $data['telefono'] = $sucursalJson['contact']['phoneNumber'];
            $data['no_ext']   = "";
            $data['no_int']   = "";

            $remitente->insertParse($data);
        }
        $data['sucursal_id']=$remitente->getId();
        $data['sucursal'] = $data['sucursal_id'];

        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);

        return $data;
    }

    public function getResponse(){
        return $this->response;
    }


}