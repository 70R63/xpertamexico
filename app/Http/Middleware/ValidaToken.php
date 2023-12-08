<?php

namespace App\Http\Middleware;

use App\Http\Controllers\API\ApiController;

use Closure;
use Illuminate\Http\Request;
use Log;
use Laravel\Sanctum\PersonalAccessToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ValidaToken extends ApiController
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
        try {
            Log::debug(__CLASS__." ".__FUNCTION__." INICIANDO-----------------");

            if(!isset($request->token))
                return $this->sendError("Error", array("El token es neceserio"), 401);
            
            Log::debug(print_r(base64_decode($request->token),true));
            $tokenDecodificado =  base64_decode($request->token);
            [$id, $token] = explode('|',$tokenDecodificado, 2);
            $personalAccessToken = PersonalAccessToken::findToken($tokenDecodificado);
            Log::debug(print_r("now  ->".Carbon::now()->toDateTimeString(),true));
            Log::debug(print_r("token->". $personalAccessToken->expires_at->toDateTimeString(),true));

            $user = Auth::user(); 
            Log::debug(print_r($user,true));

            if(is_null($personalAccessToken))
                return $this->sendError("Sin autorizacion, Valida tu registro con el proveedor", array(), 401);
            
            if (Carbon::now()->gte($personalAccessToken->expires_at->toDateTimeString()) )
                return $this->sendError("Sin autorizacion, Token expiro", array(), 401);

            
            
            $tmp = hash('sha256', $token);
            Log::debug(print_r($tmp,true));
            if (strcmp($tmp, $personalAccessToken->token) !== 0)
                return $this->sendError("Sin autorizacion, Token alterado", array(), 401);

            Log::debug(__CLASS__." ".__FUNCTION__." FINALIZANDO-----------------");
            $request['name']= $personalAccessToken->name;
            $request['user_id']= $personalAccessToken->tokenable_id;
            return $next($request);

        } catch (\InvalidArgumentException $ex) {
            Log::debug($ex );
            return $this->successResponse("Response", "InvalidArgumentException","400");

        } catch (\ErrorException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." ErrorException");
            Log::debug(print_r($ex,true));
            
            $mensaje =$ex->getMessage();
            return $this->sendError("ErrorException","Favor de contactar al administrador", "400");

        } catch (\HttpException $ex) {
            Log::info(__CLASS__." ".__FUNCTION__." HttpException");
            $resultado = $ex;
            Log::debug(print_r($ex,true));
            return $this->sendError("HttpException","valor de intercambio Http mal formado",$mensaje, "400");
        } catch (\Exception $e) {
            Log::info(__CLASS__." ".__FUNCTION__." Exception");
            return $this->sendError("Exception ", "400");
        }

    }


}
