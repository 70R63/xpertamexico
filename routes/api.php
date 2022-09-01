<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\GuiaController;
use App\Http\Controllers\API\CotizacionController;

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
    });

});

Route::group(array('domain' => env('APP_URL')), function() {
    Route::middleware(['throttle:100,1','auth'])->group(function () {
        //Route::resource('cotizacion','CotizacionController'); 
        Route::controller(CotizacionController::class)->group(function(){
           
            Route::get('create', 'create');

        });

    });
    //Fin Middileware
}); 
//Fin Domain


    