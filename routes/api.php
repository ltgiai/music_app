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

use App\Http\Controllers\ArtistController;

Route::get('/artists', [ArtistController::class, 'index']);         // Lấy danh sách nghệ sĩ
Route::get('/artists/{id}', [ArtistController::class, 'show']);     // Lấy chi tiết nghệ sĩ
Route::post('/artists', [ArtistController::class, 'store']);        // Thêm nghệ sĩ mới
Route::put('/artists/{id}', [ArtistController::class, 'update']);   // Cập nhật nghệ sĩ
Route::delete('/artists/{id}', [ArtistController::class, 'destroy']);// Xóa nghệ sĩ

