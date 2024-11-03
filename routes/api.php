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
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});




// Route::prefix('albums')->group(function () {
//     Route::get('/', [App\Http\Controllers\AlbumController::class, 'index']);  chua sua lai nha
// });

Route::prefix('/albums') -> group(function() {
    Route::get('/list-albums',[App\Http\Controllers\AlbumController::class, 'index']); // http://127.0.0.1:8000/api/albums
    Route::post('/create',[App\Http\Controllers\AlbumController::class, 'store']); // đ biết route ở đâu @@
    Route::get('/detail-album/{ma_album}',[App\Http\Controllers\AlbumController::class, 'show']); 
    Route::put('/update-album/{ma_album}',[App\Http\Controllers\AlbumController::class, 'update']); 
    Route::delete('/delete-album/{ma_album}',[App\Http\Controllers\AlbumController::class, 'destroy']); 
    Route::get('/all-artist-albums/{ma_tk}',[App\Http\Controllers\AlbumController::class, 'getAlbumsByArtistAccount']); 
    Route::get('/detail-artist-album/{ma_album}',[App\Http\Controllers\AlbumController::class, 'getSongsInAlbum']); 
    Route::post('/like-album/{ma_album}',[App\Http\Controllers\AlbumController::class, 'likeAlbum']); 
    Route::post('/like-album/{ma_album}',[App\Http\Controllers\AlbumController::class, 'unlikeAlbum']); 
    Route::get('/admin-search',[App\Http\Controllers\AlbumController::class, 'searchForAdmin']); 
    Route::get('/artist-search/{ma_tk}',[App\Http\Controllers\AlbumController::class, 'searchForArtist']); 
});