<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\TipoVehiculoController;
use App\Http\Controllers\EntradaController;
use App\Http\Controllers\SalidaController;


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
    return view('auth/login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('/vehiculos', VehiculoController::class);
Route::resource('/tipoVehiculos', TipoVehiculoController::class);
Route::resource('/entradas', EntradaController::class);
Route::resource('/salidas', SalidaController::class);


Route::get('/reporteVehiculos', [VehiculoController::class, 'reporte']);
Route::get('/reporteTipoVehiculos', [TipoVehiculoController::class, 'reporte']);
Route::get('/reporteEntrada', [EntradaController::class, 'reporte']);
Route::get('/reporteSalida', [SalidaController::class, 'reporte']);



Route::get('/tipoVehiculo', [TipoVehiculoController::class, 'index2']);
Route::get('/vehiculo', [VehiculoController::class, 'index2']);
Route::get('/entrada', [EntradaController::class, 'index2']);
Route::get('/salida', [SalidaController::class, 'index2']);


