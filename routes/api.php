<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::resource('products','\App\Http\Controllers\ProductController');
Route::get('getProducts',['\App\Http\Controllers\ProductController','getProducts']);
Route::post('saveSale',['\App\Http\Controllers\SaleController','save']);
Route::get('getSale/{id}',['\App\Http\Controllers\SaleController','get']);
