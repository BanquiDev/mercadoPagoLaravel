<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

    
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

Route::middleware('api')->post('/', 'App\Http\Controllers\MpController@mercadoPago');
    
Route::middleware('api')->post('/client', 'App\Http\Controllers\MpController@mercadoPagoClient');

Route::middleware('api')->post('/card', 'App\Http\Controllers\MpController@saveCard');

Route::middleware('api')->get('/cards', 'App\Http\Controllers\MpController@getClientCards');

Route::middleware('api')->get('/client', 'App\Http\Controllers\MpController@getClient');

Route::middleware('api')->post('/payment', 'App\Http\Controllers\MpController@payment');