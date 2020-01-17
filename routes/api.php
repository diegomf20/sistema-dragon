<?php

use Illuminate\Http\Request;

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
Route::resource('colaborador', 'ColaboradorController');
Route::resource('proveedor', 'ProveedorController');
Route::resource('insumo', 'InsumoController');
Route::resource('obra', 'ObraController');

Route::get('stock','ReporteController@stock');