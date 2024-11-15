<?php

use App\Http\Controllers\LikeSongController;
use App\Http\Controllers\SongCategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\GenreSongController;

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


// Route::apiResource('comments', CommentController::class);
// Route::apiResource('song-likes', LikeSongController::class);
// Route::apiResource('genres', GenreController::class);
// Route::apiResource('genre-songs', GenreSongController::class);

// Route CommentController
Route::get('/comments', [CommentController::class, 'index']);
Route::get('/comments/{ma_bl}', [CommentController::class, 'show']);
Route::post('/comments', [CommentController::class, 'store']);
Route::put('/comments/{ma_bl}', [CommentController::class, 'update']);
Route::delete('/comments/{ma_bl}', [CommentController::class, 'destroy']);

// Route GenreController
Route::get('/genres', [GenreController::class, 'index']);
Route::get('/genres/{ma_tl}', [GenreController::class, 'show']);
Route::post('/genres', [GenreController::class, 'store']);

// Route LikeSongController
Route::get('/song-likes', [LikeSongController::class, 'index']);
Route::get('/song-likes/{id}', [LikeSongController::class, 'show']);
Route::post('/song-likes', [LikeSongController::class, 'store']);
Route::put('/song-likes/{id}', [LikeSongController::class, 'update']);
Route::delete('/song-likes/{id}', [LikeSongController::class, 'destroy']);

// Route GenreSongController
Route::get('/genre-songs', [GenreSongController::class, 'index']);
Route::get('/genre-songs/{id}', [GenreSongController::class, 'show']);
Route::post('/genre-songs', [GenreSongController::class, 'store']);
Route::put('/genre-songs/{id}', [GenreSongController::class, 'update']);
Route::delete('/genre-songs/{id}', [GenreSongController::class, 'destroy']);
