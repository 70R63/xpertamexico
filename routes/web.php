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

Route::resource('profile','userProfileController');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

/*
|Los roles definidos son 
|- sysadmin
|- admin
|- contraloria
|- auditoria
|- comercial
|- adminops
|- operaciones
|- cliente 
|- usuario
*/
//Menu SysAdmin
Route::resource('cfgltds','CfgLtdController')
    ->middleware(['roles:sysadmin,admin']);

//Menu Clientes
Route::resource('empresas','EmpresaController')
    ->middleware(['roles:sysadmin,admin,comercial,adminops,operaciones']);
Route::resource('tarifas','TarifaController')
    ->middleware(['roles:sysadmin,admin,comercial,adminops,operaciones']);

//Menu Direcciones
Route::resource('clientes','ClienteController')
    ->middleware(['roles:sysadmin,admin,comercial,adminops,operaciones,cliente']);
Route::resource('sucursales','SucursalController')
    ->middleware(['roles:sysadmin,admin,comercial,adminops,operaciones,cliente']);

//Menu Proveedores
Route::resource('ltds','LtdController')
    ->middleware(['roles:sysadmin,admin,auditoria.comercial']);
Route::resource('coberturas','CoberturasController')
    ->middleware(['roles:sysadmin,admin,auditoria.comercial']);

//Menu Usuario
Route::resource('users','Roles\UsersController')
    ->middleware(['roles:sysadmin,admin,comercial,adminops,cliente']);

//Menu guia
Route::resource('guia','GuiaController')
    ->middleware(['roles:sysadmin,admin,operaciones,cliente,usuario']);
Route::resource('cotizaciones','CotizadorController')
    ->middleware(['roles:sysadmin,admin,operaciones,cliente,usuario']);

//Menu Roles
Route::resource('roles','Roles\RolesController')
    ->middleware(['roles:sysadmin,admin']);
/*

// ROL ADMIN 
Route::middleware(['roles:admin'])->group(function(){
    
    
    

});
//FIN ROL ADMIN

// ROL SYSADMIN 
Route::middleware(['roles:sysadmin'])->group(function(){
    //Menu SysAdmin
    //Route::resource('cfgltds','CfgLtdController');
    //Menu Clientes
    Route::resource('empresas','EmpresaController');
    Route::resource('tarifas','TarifaController');
    //Menu Proveedores
    Route::resource('clientes','ClienteController');
    Route::resource('sucursales','SucursalController');
    //Menu Proveedores
    Route::resource('ltds','LtdController');
    Route::resource('coberturas','CoberturasController');
    //Menu Usuario
    Route::resource('users','Roles\UsersController');
    //Menu guia
    Route::resource('guia','GuiaController');
    Route::resource('cotizaciones','CotizadorController');
    //Menu Roles
    Route::resource('roles','Roles\RolesController');
    
});
//FIN ROL SYSADMIN



// ROL CONTRALORIA 
Route::middleware(['roles:contraloria'])->group(function(){
    
});
//FIN ROL CONTRALORIA

// ROL AUDITORIA 
Route::middleware(['roles:auditoria'])->group(function(){
    //Menu Proveedores
    Route::resource('ltds','LtdController');
    Route::resource('coberturas','CoberturasController');
});
//FIN ROL AUDITORIA

// ROL COMERCIAL 
Route::middleware(['roles:comercial'])->group(function(){
    //Menu Clientes
    Route::resource('empresas','EmpresaController');
    Route::resource('tarifas','TarifaController');
    //Menu Direcciones
    Route::resource('clientes','ClienteController');
    Route::resource('sucursales','SucursalController');
    //Menu Proveedores
    Route::resource('ltds','LtdController');
    Route::resource('coberturas','CoberturasController');
    //Menu Usuario
    Route::resource('users','Roles\UsersController');
    
});
//FIN ROL COMERCIAL

// ROL ADMIN OPERACIONES 
Route::middleware(['roles:adminops'])->group(function(){
    //Menu Clientes
    Route::resource('empresas','EmpresaController');
    Route::resource('tarifas','TarifaController');
    //Menu Direcciones
    Route::resource('clientes','ClienteController');
    Route::resource('sucursales','SucursalController');
    //Menu Usuario
    Route::resource('users','Roles\UsersController');
     
});
//FIN ROL ADMIN OPERACIONES

// ROL OPERACIONES 
Route::middleware(['roles:operaciones'])->group(function(){
    //Menu Clientes
    Route::resource('empresas','EmpresaController');
    Route::resource('tarifas','TarifaController');
    //Menu Direcciones
    Route::resource('clientes','ClienteController');
    Route::resource('sucursales','SucursalController');
    //Menu guia
    Route::resource('guia','GuiaController');
    Route::resource('cotizaciones','CotizadorController');
    
});
//FIN ROL OPERACIONES

// ROL CLIENTE 
Route::middleware(['roles:cliente'])->group(function(){
   //Menu Direcciones
    Route::resource('clientes','ClienteController');
    Route::resource('sucursales','SucursalController');
    //Menu Usuario
    Route::resource('users','Roles\UsersController');
    //Menu guia
    Route::resource('guia','GuiaController');
    Route::resource('cotizaciones','CotizadorController');
     
});
//FIN ROL CLIENTE

// ROL USUARIO 
Route::middleware(['roles:usuario'])->group(function(){
    //Menu guia
    Route::resource('guia','GuiaController');
    Route::resource('cotizaciones','CotizadorController');
    
});
//FIN ROL USUARIO

*/


require __DIR__.'/auth.php';
