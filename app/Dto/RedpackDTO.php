<?php
namespace App\Dto;

use Log;
use Carbon\Carbon;
use Config;

use App\Models\RangoGuias;


/**
 * 
 */
class RedpackDTO 
{
    /**
     * Insert , array que conse usara para insertar como si fura un request
     *
     * @var nombre
     */
    public $insert;
    private $rangoExedido = false;
    
    function __construct()
    {
        // code...
    }

    /**
     * Metodo para asignar los valores del request a una etiqueta 
     * para generar el venvio a la api de redpack
     * https://api.redpack.com.mx/api-redpack/services/rest
     * 
     * @param \Illuminate\Http\Request $request
     * @return array body
     */



    public function parser($request){
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." INICIANDO");
        $parcels = array();

        if (count($request['pesos']) == $request['piezas']) {
          for ($i=0; $i < $request['piezas']; $i++) { 
            $parcels[]= array (
                    'description' => $request['contenido'],
                    'piece' => 1,
                    'weigth' => $request['pesos'][$i],
                    'high' => $request['altos'][$i],
                    'length' => $request['largos'][$i],
                    'width' => $request['anchos'][$i],
                  );
          }
        } else {
          $interacion = count ($request['pesos']) -1 ;
          for ($i=0; $i < $request['piezas']; $i++) { 
            $parcels[]= array (
                    'description' => $request['contenido'],
                    'piece' => 1,
                    'weigth' => $request['pesos'][$interacion],
                    'high' => $request['altos'][$interacion],
                    'length' => $request['largos'][$interacion],
                    'width' => $request['anchos'][$interacion],
                  );
          }  
        }

        $rangoGuiasGeneral = RangoGuias::where('ltd_id',Config('ltd.redpack.id'))
                        ->where('servicio_id', $request['servicio_id']);
                      

        $actualTracking = $rangoGuiasGeneral->latest()->value('actual');
        $rangoGuias = $rangoGuiasGeneral->get()->toArray();
        
        $trackingNumber = $actualTracking +1;
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." VALIDANDO DISPONIBILIDAD DE RANGO");
        if ($trackingNumber > $rangoGuias[0]['final']){
            $this->rangoExedido = true;

            return false;
        }

        RangoGuias::create(array('inicial' => $rangoGuias[0]['inicial']
                      ,'final' => $rangoGuias[0]['final']
                      ,'actual'=> $trackingNumber
                      ,'ltd_id'=> Config('ltd.redpack.id')
                      ,'servicio_id'=> $request['servicio_id']
                    )
                  );
        
        
        

        $body = array ( 
              array (
                'deliveryType' => 
                array (
                  'id' => 2,
                ),
                'trackingNumber' => $trackingNumber,
                'observations' => 'Observaciones D',
                'idClient' => Config('ltd.redpack.idClient'),
                'nationalCurrency' => 'MXN',
                'origin' => 
                array (
                  'city' => $request['ciudad'],
                  'company' => $request['nombre'],
                  'country' => "Mexico",
                  'name' => $request['contacto'],
                  'email' => 'test@test.com',
                  'originRfc' => 'XAXX010101000',
                  'phones' => 
                  array (
                    array (
                      'phone' => $request['celular_d'],
                    ),
                  ),
                  'reference3' => 'REFERENCIA 3 D',
                  'externalNumber'=> $request['no_ext'],
                  'internalNumber'=> $request['no_int'],
                  'state' => $request['entidad_federativa'],
                  'street' => $request['direccion'],
                  'suburb' => $request['colonia'],
                  'zipCode' => $request['cp'],
                ),
                'parcels' => $parcels
               
                ,'reference2' => 'REFERENCIA 2 D',
                'reference' => 'REFERENCIA 1 D',
                'printType' => 5,
                'serviceType' => 
                array (
                  'id' => Config('ltd.redpack.servicio')[$request['servicio_id']],
                ),
                'shippingType' => 
                array (
                  'id' => 1,
                ),
                'shippingValue' => $request['valor_envio'],
                'target' => 
                array (
                  'city' => $request['ciudad_d'],
                  'company' => $request['nombre_d'],
                  'country' => "Mexico",
                  'name' => $request['contacto_d'],
                  'email' => 'test@test.com',
                  'originRfc' => 'XAXX010101000',
                  'phones' => 
                  array (
                    array (
                      'phone' => $request['celular_d'],
                    ),
                  ),
                  'reference3' => 'REFERENCIA 3 D',
                  'externalNumber'=> $request['no_ext_d'],
                  'internalNumber'=> $request['no_int_d'],
                  'state' => $request['entidad_federativa_d'],
                  'street' => $request['direccion_d'],
                  'suburb' => $request['colonia_d'],
                  'zipCode' => $request['cp_d'],
                ),
              ),
            );

        return $body;
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__." FINALIZANDO");

    }


    public function getRangoExedido(){
        return $this->rangoExedido;
    }
}

?>
