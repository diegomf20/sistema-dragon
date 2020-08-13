<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::post('retorno','RetornoController@store');

Route::post('activo/{id}/obra','ActivoController@asignarObra');
Route::resource('activo', 'ActivoController');
Route::resource('colaborador', 'ColaboradorController');
Route::resource('proveedor', 'ProveedorController');
Route::resource('cliente', 'ClienteController');
Route::resource('unidad', 'UnidadController');
Route::resource('insumo', 'InsumoController');
Route::post('obra/{id}/finalizar','ObraController@finalizar');
Route::resource('obra', 'ObraController');
Route::resource('consumo', 'ConsumoController');
Route::get('consumo', 'MovimientoController@getConsumo');
Route::resource('compra', 'CompraController');
Route::resource('cuadre', 'CuadreController');
Route::resource('gasto', 'GastoController');


Route::get('stock','ReporteController@stock');
Route::get('kardex_unitario','ReporteController@kardex_unitario');
Route::get('reorden','ReporteController@reorden');
Route::get('resumen-obra','ReporteController@resumen_obra');
Route::resource('movimiento','MovimientoController');

Route::get('exports/kardex','ExportsController@kardex');