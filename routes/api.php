<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PetshopController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\BookingController;
use App\http\Controllers\TransaksiController;
use App\Http\Controllers\HariController;

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
Route::get('/hari', [HariController::class, 'allHari']);
Route::post('/hari/{id}', [HariController::class, 'pilihHari']);
Route::post('/signup-customer', [UserController::class, 'regisCustomer']);

Route::get('/jenis-produk', [ProdukController::class, 'getJnsProduk']);
Route::post('/jenis-produk', [ProdukController::class, 'createJnsProduk']);

Route::get('/produk/{jumlah}', [ProdukController::class, 'allProduk']);
Route::get('/produk-by-id/{id}', [ProdukController::class, 'produkById']);
Route::get('/foto-produk/{filename}', [ProdukController::class, 'image']);
Route::get('/layanan/{jumlah}', [LayananController::class, 'allLayanan']);
Route::get('/layanan-by-id/{id}', [LayananController::class, 'layananById']);
Route::get('/foto-layanan/{filename}', [LayananController::class, 'image']);

Route::get('/petshop-name', [PetshopController::class, 'allPetshopName']);
Route::get('/foto-petshop/{filename}', [UserController::class, 'fotoPetshop']);
Route::get('/petshop/{id}', [UserController::class, 'hariPetshop']);
Route::get('/hari-petshop/{petshop}', [HariController::class, 'hariPetshop']);

Route::get('/product-petshop/{petshop}', [ProdukController::class, 'produkByPetshop']);
Route::get('/layanan-petshop/{petshop}', [LayananController::class, 'LayananByPetshop']);

Route::get('/cari-produk/{produk}', [ProdukController::class, 'cariProduk']);
Route::get('/cari-layanan/{layanan}', [LayananController::class, 'cariLayanan']);

Route::middleware('auth:api')->group(function(){
    Route::get('/user/{id}', [UserController::class, 'userById']);
    Route::delete('/delete-hari/{id}', [HariController::class, 'deleteHari']);
    Route::post('/user/{id}', [UserController::class, 'updateCustomer']);
    Route::get('/foto-customer/{fileName}', [UserController::class, 'image']);
    
    Route::get('/all-petshop', [PetshopController::class, 'allPetshop']);
    Route::get('/all-customer', [PetshopController::class, 'allCustomer']);
    
    // PETSHOP 
    Route::post('/produk/{id}', [ProdukController::class, 'createProduk']);
    Route::post('/layanan/{id}', [LayananController::class, 'createLayanan']);
    Route::get('/transaksi-confirmed/{petshop}/{status}', [TransaksiController::class, 'transaksiConfirmedPetshop']);
    Route::get('/pesanan-by-transaksi/{idTransaksi}', [TransaksiController::class, 'pesananTransaksi']);
    Route::put('/set-transaksi/{id}', [TransaksiController::class, 'setStatusTransaksi']);
    Route::get('/kategori-layanan/{petshop}', [BookingController::class, 'kategoriLayananByPetshop']);
    Route::get('/layanan-by-katogori-petshop/{petshop}/{kategori}', [BookingController::class, 'layananPetshopByKategori']);
    Route::post('/booking-offline/{petshop}', [BookingController::class, 'bookingOffline']);

    Route::get('/petshop-booking/{id}/{status}', [BookingController::class, 'layananBookedByPetshop']);
    Route::put('/booking-status/{id}', [BookingController::class, 'setStatusBooking']);
    
    Route::post('/transaksi-offline/{petshop}', [TransaksiController::class, 'checkoutTransaksiOffline']);
    //BOOKING
    Route::post('/booking/{id}', [BookingController::class, 'pesan']);
    Route::get('/booking/{booking}', [BookingController::class, 'bookingById']);
    Route::get('/booking-customer/{customer}/{status}', [BookingController::class, 'layananByCustomer']);
    Route::delete('/delete-booking/{idBooking}', [BookingController::class, 'deleteBooking']);
    // PESANAN PRODUK
    Route::get('/transaksi/{customer}', [TransaksiController::class, 'transaksiByCustomer']);
    Route::get('/transaksi-by-id/{id}', [TransaksiController::class, 'transaksiById']);
    Route::get('/pesanan/{pesanan}', [TransaksiController::class, 'pesananById']);
    Route::get('/bukti-tf/{filename}', [TransaksiController::class, 'image']);
    Route::put('/pesanan/{id}', [TransaksiController::class, 'updateJumlah']);
    Route::delete('/pesanan/{produk}', [TransaksiController::class, 'hapusPesanan']);
    Route::post('/pesan-produk/{id}', [TransaksiController::class, 'pesan']);
    Route::get('/checkout/{customer}/{idTransaksi}', [TransaksiController::class, 'checkout']);
    Route::post('/checkout/{transaksi}', [TransaksiController::class, 'uploadBukti']);
    
    // ADMIN
    Route::get('/count-summary', [UserController::class, 'countingAll']);
    Route::get('/transaksi-konfimasi', [TransaksiController::class, 'toConfirmAdmin']);
    Route::get('/transaksi-detail/{transaksi}', [TransaksiController::class, 'transaksiDetail']);
    Route::put('/status-transaksi/{id}', [TransaksiController::class, 'updateStatus']);
    
    Route::get('/petshop-to-confirm', [PetshopController::class, 'petshopToConfirm']);
    Route::get('/verif-petshop/{id}', [PetshopController::class, 'verifPetshop']);
});

// Route::get('home', fn() => 'sapi')->w;



