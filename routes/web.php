<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LtdController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

//ACCESO A TODO LOS ROLES DISPONIBLES
Route::middleware(['roles:sysadmin,admin,ejecutivo,cliente,usuario'])->group(function(){
    Route::resource('guia','GuiaController');
});
//FIN USUARIO

//
Route::middleware(['roles:sysadmin,admin,ejecutivo,cliente'])->group(function(){
    Route::resource('users','Roles\UsersController');
});
//FIN 

//
Route::middleware(['roles:sysadmin,admin,ejecutivo'])->group(function(){
    
    Route::resource('ltds','LtdController');
});
//FIN 

Route::middleware(['roles:sysadmin,admin'])->group(function(){
    Route::resource('roles','Roles\RolesController');
});
//FIN 

Route::middleware(['roles:sysadmin,admin'])->group(function(){
    Route::resource('coberturas','CoberturasController');
    Route::resource('tarifas','TarifaController');
    Route::resource('cotizaciones','CotizadorController');
    Route::resource('clientes','ClienteController');
    Route::resource('sucursales','SucursalController');
    Route::resource('empresas','EmpresaController');

});//FIN DEL MIDDLEWARE PARA ADMINISTRATIVOS

Route::middleware(['roles:sysadmin,admin'])->group(function(){
    Route::resource('cfgltds','CfgLtdController');

});//FIN DEL MIDDLEWARE

Route::resource('profile','userProfileController');



require __DIR__.'/auth.php';
