<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\LoginController  as AuthController;
use App\Http\Controllers\API\GuiaController;
use App\Http\Controllers\API\CotizacionController;
use App\Http\Controllers\API\EmpresaLtdController;

use App\Http\Controllers\API\DEV\GuiaController as DevGuiaController ;


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

Route::post('/register',[AuthController::class,'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/ping', function (Request $request) {
    
    return response()->json([
            'status' => true,
            'message' => "Ping successfully!",
        ], 200);
});

//Route::domain('local.xpertamexico.com')->group(function () {
    Route::middleware(['throttle:100,1','validaToken'])->group(function(){
        Route::post('logout', [AuthController::class, 'logout']);
        
        Route::controller(GuiaController::class)->group(function(){
            Route::get('ltds', 'creacion');
            Route::post('fedex', 'fedex');
            Route::post('estafeta', 'estafeta');
            Route::post('dev/estafeta', 'estafeta');
            Route::get('rastreoTabla', 'rastreoTabla');
        });

    });
//});


Route::middleware(['throttle:100,1','auth'])->group(function () {
    Route::name('api.')->group(function () {
        Route::apiResource('cotizaciones', CotizacionController::class);

        Route::controller(CotizacionController::class)->group(function(){
            Route::get('cp', 'cp');    
        });

        Route::apiResource('empresaltd', EmpresaLtdController::class);

        Route::controller(GuiaController::class)->group(function(){
            Route::get('rastreoTabla', 'rastreoTabla');
            Route::post('rastreoActualizar', 'rastreoActualizar');
        });
    });
});
//Fin Middileware

//AMBIENTE DEV
Route::middleware(['throttle:20,1','validaToken'])->group(function(){
    Route::controller(DevGuiaController::class)->group(function(){
        Route::post('dev/estafeta', 'estafeta');
    });

});
    
