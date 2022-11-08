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
    'fedex' => ['base_uri' => env('FEDEX_BASEURI')]
    ,'estafeta' =>[
        'base_uri' => env('ESTAFETA_BASEURI')
    ]
];