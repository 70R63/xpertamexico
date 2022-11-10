<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Configuracion para LTDs
    |--------------------------------------------------------------------------
    |
    | Se agregan las rutas de los wsdl para los LTD
    |
    */

    'estafeta'      => env('WSDL_ESTAFETA', 'Laravel'),
    'estafeta_tracking' => env('WSDL_ESTAFETA_TRACKING_DEV', 'tracking'),
    'fedex' => [
        'id'    => "1"
        ,'base_uri'  => env('FEDEX_BASEURI')
    ]
    ,'estafeta' =>[
        'id'    => "2"
        ,'base_uri'  =>  env('ESTAFETA_BASEURI')
        ,'token'    =>  env('ESTAFETA_TOKEN')
        ,'api_key'  =>  env('ESTAFETA_APIKEY')
    ]
];