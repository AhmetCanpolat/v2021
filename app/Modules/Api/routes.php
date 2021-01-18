<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Api Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::group(['middleware' => ['auth:api']], function () {

    Route::post('/shipment/create', 'CreateShipmentController@handle')->name('shipment.create');
    Route::get('/shipment/info', 'GetShipmentInfoController@handle')->name('shipment.info');
    Route::get('/shipment/track', 'TrackShipmentController@handle')->name('shipment.track');
    Route::get('/shipment/label', 'GetLabelController@handle')->name('shipment.label');
    Route::get('/shipment/printed', 'ReportLabelPrintController@handle')->name('shipment.printed');
    Route::get('/shipment/zone-update', 'ShippimentZoneUpdateController@handle')->name('shipment.zone-update');

});

