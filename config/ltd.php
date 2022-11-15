<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Configuracion para LTDs
    |--------------------------------------------------------------------------
    |
    | LTD.ID valor para decalro en la tabla ltd
    |
    |
    |
    |El valor servicio es el Id delcaro en la tabla servicios
    |en caso de poner un valor mal el tipo de servicio puede causar una guia con valores distintos
    |1) = Terrestre, 2) Siguientes dia, 3) dos dias, los valores debran ajustarse para cada LTD
    |
    |
    */

    'estafeta'      => env('WSDL_ESTAFETA', 'Laravel'),
    'estafeta_tracking' => env('WSDL_ESTAFETA_TRACKING_DEV', 'tracking'),
    'fedex' => [
        'id'            => "1"
        ,'base_uri'     => env('FEDEX_BASEURI')
        ,'client_id'    => env('FEDEX_CLIENT_ID')
        ,'client_secret'=> env('FEDEX_CLIENT_SECRET')
        ,'servicio' => [
            '1'     => 'FEDEX_EXPRESS_SAVER'
            ,'2'    => 'STANDARD_OVERNIGHT'
            ,'3'    => 'STANDARD_OVERNIGHT'
        ]
        ,'cred'     => [
            'accountNumber' => env('FEDEX_ACCOUNTNUMBER')
        ]
    ]
    ,'estafeta' =>[
        'id'    => "2"
        ,'base_uri'  =>  env('ESTAFETA_BASEURI')
        ,'token'    =>  env('ESTAFETA_TOKEN')
        ,'api_key'  =>  env('ESTAFETA_APIKEY')
        ,'servicio' => [
            '1'     => '70'
            ,'2'    => '60'
            ,'3'    => 'D0'
        ]
        ,'cred'     => [
            'suscriberId' => env('ESTAFETA_SUSCRIBERID')
            ,'customerNumber' => env('ESTAFETA_CUSTOMERNUMBER')
            ,'salesOrganization'=>env('ESTAFETA_SALESORGANIZATION')
        ]
    ]
];