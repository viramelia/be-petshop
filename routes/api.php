<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PetshopController;
use App\Http\Controllers\ProdukController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth', [UserController::class, 'login']);
Route::post('/signup-petshop', [UserController::class, 'regisPetshop']);
Route::post('/signup-customer', [UserController::class, 'regisCustomer']);

Route::middleware('auth:api')->group(function(){
    Route::get('/all-petshop', [PetshopController::class, 'allPetshop']);
    Route::post('/verif-petshop', [PetshopController::class, 'verifPetshop']);
    Route::get('/all-customer', [PetshopController::class, 'allCustomer']);

    Route::post('/jenis-produk', [ProdukController::class, 'createJnsProduk']);
    Route::post('/produk', [ProdukController::class, 'createProduk']);
});

// Route::get('home', fn() => 'sapi')->w;



