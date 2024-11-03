<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\DecentralizationController;
use App\Http\Controllers\FunctionnController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ArtistController;
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
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/artists', [ArtistController::class, 'index']);         // Lấy danh sách nghệ sĩ
Route::get('/artists/{id}', [ArtistController::class, 'show']);     // Lấy chi tiết nghệ sĩ
Route::post('/artists', [ArtistController::class, 'store']);        // Thêm nghệ sĩ mới
Route::put('/artists/{id}', [ArtistController::class, 'update']);   // Cập nhật nghệ sĩ
Route::delete('/artists/{id}', [ArtistController::class, 'destroy']);// Xóa nghệ sĩ

// Route AccountController
Route::get('/accounts', [AccountController::class, 'index']);
Route::get('/accounts/{ma_tk}', [AccountController::class, 'show']);
Route::post('/accounts', [AccountController::class, 'store']);
Route::put('/accounts/{ma_tk}', [AccountController::class, 'update']);
Route::delete('/accounts/{ma_tk}', [AccountController::class, 'destroy']);

// Route UserController
Route::get('/users', [UserController::class, 'index']);
Route::put('/users/{ma_tk}', [UserController::class, 'update']);

// Route DecentralizationController
Route::get('/decentralizations', [DecentralizationController::class, 'index']);
Route::get('/decentralizations/{ma_phan_quyen}', [DecentralizationController::class, 'show']);
Route::post('/decentralizations', [DecentralizationController::class, 'store']);
Route::put('/decentralizations/{ma_phan_quyen}', [DecentralizationController::class, 'update']);
Route::delete('/decentralizations/{ma_phan_quyen}', [DecentralizationController::class, 'destroy']);
Route::post('/decentralizations/{ma_phan_quyen}/attach-chuc-nang/{ma_chuc_nang}', [DecentralizationController::class, 'attachFunctionn']);

// Route FunctionController
Route::get('/functionns', [FunctionnController::class, 'index']);
Route::get('/functionns/{ma_chuc_nang}', [FunctionnController::class, 'show']);
Route::post('/functionns', [FunctionnController::class, 'store']);
Route::put('/functionns/{ma_chuc_nang}', [FunctionnController::class, 'update']);
Route::delete('/functionns/{ma_chuc_nang}', [FunctionnController::class, 'destroy']);

// Route NotificationController
Route::get('/notifications', [NotificationController::class, 'index']);
Route::get('/notifications/{ma_tb}', [NotificationController::class, 'show']);
Route::post('/notifications', [NotificationController::class, 'store']);

