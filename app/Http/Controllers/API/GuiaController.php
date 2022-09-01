<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as Controller;
use Illuminate\Http\Request;
use Log;
use Laravel\Sanctum\HasApiTokens;
use App\Dto\Estafeta;
class GuiaController extends Controller
{
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function Creacion(Request $request)
    {
        Log::info(__CLASS__." ".__FUNCTION__);

        //Log::info($request);
        $guia = new Estafeta();

        //$request->user()->currentAccessToken()->delete();
        return $guia -> init( $request );

        
    }
}
