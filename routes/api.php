<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\GuiaController;
use App\Http\Controllers\API\CotizacionController;
use App\Http\Controllers\API\EmpresaLtdController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(LoginController::class)->group(function(){
    Route::post('login', 'login');
    Route::get('registro', 'register');

});

Route::middleware('auth:sanctum')->get('/ping', function (Request $request) {
    
    return response()->json([
            'status' => true,
            'message' => "Ping successfully!",
        ], 200);
});


Route::middleware('auth:sanctum')->group(function(){
    Route::controller(GuiaController::class)->group(function(){
        Route::get('ltds', 'creacion');
        Route::post('fedex', 'fedex');
    });

});


Route::group(array('domain' => env('APP_URL')), function() {
    Route::middleware(['throttle:100,1','auth'])->group(function () {
        Route::name('api.')->group(function () {
            Route::apiResource('cotizaciones', CotizacionController::class);

            Route::controller(CotizacionController::class)->group(function(){
                Route::get('cp', 'cp');    
            });

            Route::apiResource('empresaltd', EmpresaLtdController::class);

        });

    });
    //Fin Middileware
}); 
//Fin Domain


    