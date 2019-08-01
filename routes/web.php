<?php

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
    return view('welcome');
});

///////////////////
// Rutas archivo //
///////////////////

Route::post('archivo/getArchivos', 'ArchivoController@getArchivos')->name('archivo.getArchivos');
Route::post('archivo/import/{archivo}', 'ArchivoController@import')->name('archivo.import');

Route::resource('archivo', 'ArchivoController');

Route::post('archivo/getArchivos', 'ArchivoController@getArchivos')->name('archivo.getArchivos');

//////////////////////////
// Rutas liquidacionMes //
//////////////////////////

Route::resource('liquidacionMes', 'LiquidacionMesController');
