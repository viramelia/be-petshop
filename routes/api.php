<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PetshopController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\LayananController;

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

// ALL ACCESS
Route::post('/auth', [UserController::class, 'login']);
Route::post('/signup-petshop', [UserController::class, 'regisPetshop']);
Route::post('/signup-customer', [UserController::class, 'regisCustomer']);

Route::get('/jenis-produk', [ProdukController::class, 'getJnsProduk']);
Route::post('/jenis-produk', [ProdukController::class, 'createJnsProduk']);

Route::get('/produk', [ProdukController::class, 'allProduk']);
Route::get('/produk/{id}', [ProdukController::class, 'produkById']);
Route::get('/produk', [LayananController::class, 'allLayanan']);
Route::get('/produk/{id}', [LayananController::class, 'layananById']);

Route::middleware('auth:api')->group(function(){
    Route::get('/all-petshop', [PetshopController::class, 'allPetshop']);
    Route::post('/verif-petshop', [PetshopController::class, 'verifPetshop']);
    Route::get('/all-customer', [PetshopController::class, 'allCustomer']);

    Route::post('/produk', [ProdukController::class, 'createProduk']);

    Route::post('/layanan', [LayananController::class, 'createLayanan']);
});

// Route::get('home', fn() => 'sapi')->w;



