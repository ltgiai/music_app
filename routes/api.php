<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/albums') -> group(function() {
    Route::get('/list-albums',[App\Http\Controllers\AlbumController::class, 'index']); // http://127.0.0.1:8000/api/albums
    Route::post('/create',[App\Http\Controllers\AlbumController::class, 'store']); 
    Route::get('/detail-album/{ma_album}',[App\Http\Controllers\AlbumController::class, 'show']); 
    Route::put('/update-album/{ma_album}',[App\Http\Controllers\AlbumController::class, 'update']); 
    Route::delete('/delete-album/{ma_album}',[App\Http\Controllers\AlbumController::class, 'destroy']); 
});

Route::prefix('/advertisements') -> group(function() {
    Route::get('/list-advertisements',[App\Http\Controllers\AdvertisementController::class, 'index']); // http://127.0.0.1:8000/api/advertisements
    Route::post('/create',[App\Http\Controllers\AdvertisementController::class, 'store']); 
    Route::get('/detail-advertisement/{ma_quang_cao}',[App\Http\Controllers\AdvertisementController::class, 'show']); 
    Route::put('/update-advertisements/{ma_quang_cao}',[App\Http\Controllers\AdvertisementController::class, 'update']); 
    Route::delete('/delete-advertisements/{ma_quang_cao}',[App\Http\Controllers\AdvertisementController::class, 'destroy']); 
});

Route::prefix('/advertisers') -> group(function() {
    Route::get('/list-advertisers',[App\Http\Controllers\AdvertiserController::class, 'index']); // http://127.0.0.1:8000/api/advertisers
    Route::post('/create',[App\Http\Controllers\AdvertiserController::class, 'store']); 
    Route::get('/detail-advertiser/{ma_nqc}',[App\Http\Controllers\AdvertiserController::class, 'show']); 
    Route::put('/update-advertiser/{ma_nqc}',[App\Http\Controllers\AdvertiserController::class, 'update']); 
    Route::delete('/delete-advertiser/{ma_nqc}',[App\Http\Controllers\AdvertiserController::class, 'destroy']); 
});

Route::prefix('/advertising-contracts') -> group(function() {
    Route::get('/list-advertising-contracts',[App\Http\Controllers\AlbumController::class, 'index']); // http://127.0.0.1:8000/api/advertising-contracts
    Route::post('/create',[App\Http\Controllers\AlbumController::class, 'store']); 
    Route::get('/detail-advertising_contract/{ma_nqc}/{ma_quang_cao}',[App\Http\Controllers\AlbumController::class, 'show']); 
    Route::put('/update-advertising_contract/{ma_nqc}/{ma_quang_cao}',[App\Http\Controllers\AlbumController::class, 'update']); 
    Route::delete('/delete-advertising_contract/{ma_nqc}/{ma_quang_cao}',[App\Http\Controllers\AlbumController::class, 'destroy']); 
});

Route::prefix('/vouchers') -> group(function() {
    Route::get('/list-vouchers',[App\Http\Controllers\VoucherController::class, 'index']); // http://127.0.0.1:8000/api/vouchers
    Route::post('/create',[App\Http\Controllers\VoucherController::class, 'store']); 
    Route::get('/detail-voucher/{ma_goi}',[App\Http\Controllers\VoucherController::class, 'show']); 
    Route::put('/update-voucher/{ma_goi}',[App\Http\Controllers\VoucherController::class, 'update']); 
    Route::delete('/delete-voucher/{ma_goi}',[App\Http\Controllers\VoucherController::class, 'destroy']); 
});

Route::prefix('/voucher-registers') -> group(function() {
    Route::get('/list-voucher-registers',[App\Http\Controllers\VoucherRegisterController::class, 'index']); // http://127.0.0.1:8000/api/voucher-registers
    Route::post('/create',[App\Http\Controllers\VoucherRegisterController::class, 'store']); 
    Route::get('/detail-voucher-register/{ma_tk}/{ma_goi}',[App\Http\Controllers\VoucherRegisterController::class, 'show']); 
    Route::put('/update-voucher-register/{ma_tk}/{ma_goi}',[App\Http\Controllers\VoucherRegisterController::class, 'update']); 
    Route::delete('/delete-voucher-register/{ma_tk}/{ma_goi}',[App\Http\Controllers\VoucherRegisterController::class, 'destroy']); 
});