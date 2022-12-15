<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Log;
use Laravel\Sanctum\PersonalAccessToken;
use Carbon\Carbon;

class ValidaToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        Log::debug(__CLASS__." ".__FUNCTION__." INICIANDO-----------------");

        Log::debug(print_r(base64_decode($request->token),true));
        $tokenDecodificado =  base64_decode($request->token);
        [$id, $token] = explode('|',$tokenDecodificado, 2);
        $personalAccessToken = PersonalAccessToken::findToken($tokenDecodificado);
        
        if(is_null($personalAccessToken))
            return $this->sendError("Sin autorizacion, Valida tu registro con el proveedor", array(), 401);
        
        if (Carbon::now()->gte($personalAccessToken->expires_at->toDateTimeString()) )
            return $this->sendError("Sin autorizacion, Token expiro", array(), 401);

        Log::debug(print_r(Carbon::now()->toDateTimeString(),true));
        Log::debug(print_r($personalAccessToken->expires_at->toDateTimeString(),true));
        
        $tmp = hash('sha256', $token);
        Log::debug(print_r($tmp,true));
        if (strcmp($tmp, $personalAccessToken->token) !== 0)
            return $this->sendError("Sin autorizacion, Token alterado", array(), 401);

        Log::debug(__CLASS__." ".__FUNCTION__." FINALIZANDO-----------------");
        $request['name']= $personalAccessToken->name;
        return $next($request);
    }


    /**
     * return error response.
     * Funcion temporal en lo que se valida la utenticacion 
     * 
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];


        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }
}
