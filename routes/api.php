<?php

use App\Http\Controllers\LikeSongController;
use App\Http\Controllers\SongCategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\DecentralizationController;
use App\Http\Controllers\FunctionnController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\GenreSongController;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\AdvertiserController;
use App\Http\Controllers\AdvertisingContractController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\VoucherRegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlaylistController;

// Route SongController
Route::get('/songs', [SongController::class, 'renderListOfSongs']); // Liệt kê danh sách bài hát trên trang chủ
Route::get('/songs/collab', [SongController::class, 'renderListOfSongsWithCollabArtist']); // Liệt kê danh sách bài hát có subartist
Route::get('/songs/artists', [SongController::class, 'renderListOfArtists']); // Liệt kê danh sách nghệ sĩ
Route::get('/songs/artist', [SongController::class, 'renderListOfSongsInEveryArtist']); // Liệt kê danh sách nghệ sĩ
Route::get('/song/{ma_bai_hat}', [SongController::class, 'renderSongDetails']); // Tìm kiếm bài hát theo mã bài hát
Route::post('/song', [SongController::class, 'store']); // Thêm một bài hát
Route::put('/song/{ma_bai_hat}', [SongController::class, 'update']); // Chỉnh sửa bài hát dựa vào mã bài hát
Route::delete('/song/{ma_bai_hat}', [SongController::class, 'destroy']); // Chỉnh sửa trạng thái bài hát dựa vào mã bài hát

// Route Playlist
Route::get('/playlists', [PlaylistController::class, 'renderListOfPlaylists']); // Liệt kê danh sách playlist có trong hệ thống

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
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rote Album
Route::get('/albums/list-albums', [App\Http\Controllers\AlbumController::class, 'index']);
Route::post('/albums/{ma_tk}', [App\Http\Controllers\AlbumController::class, 'store'])->where('ma_album', 'ACC\d{4}');
Route::put('/albums/{ma_album}/add', [App\Http\Controllers\AlbumController::class, 'addSongsToAlbum']);
Route::get('/albums/{ma_album}', [App\Http\Controllers\AlbumController::class, 'show'])->where('ma_album', 'AL\d{4}');
Route::put('/albums/{ma_album}', [App\Http\Controllers\AlbumController::class, 'update']);
Route::delete('/albums/{ma_album}', [App\Http\Controllers\AlbumController::class, 'destroy']);
Route::get('/albums/artist/{ma_tk}', [App\Http\Controllers\AlbumController::class, 'getAlbumsByArtistAccount']);
Route::get('/albums{ma_album}/songs', [App\Http\Controllers\AlbumController::class, 'getSongsInAlbum']);
Route::post('/albums/{ma_album}/like', [App\Http\Controllers\AlbumController::class, 'likeAlbum']);
Route::post('/albums/{ma_album}/unlike', [App\Http\Controllers\AlbumController::class, 'unlikeAlbum']);

// Route AdvertisementController
Route::get('/advertisements', [AdvertisementController::class, 'index']);
Route::get('/advertisements/{id}', [AdvertisementController::class, 'show']);
Route::post('/advertisements', [AdvertisementController::class, 'store']);
Route::put('/advertisements/{id}', [AdvertisementController::class, 'update']);
Route::delete('/advertisements/{id}', [AdvertisementController::class, 'destroy']);

// Route AdvertiserController
Route::get('/advertisers', [AdvertiserController::class, 'index']);
Route::get('/advertisers/{id}', [AdvertiserController::class, 'show']);
Route::post('/advertisers', [AdvertiserController::class, 'store']);
Route::put('/advertisers/{id}', [AdvertiserController::class, 'update']);
Route::delete('/advertisers/{id}', [AdvertiserController::class, 'destroy']);

// Route AdvertisingContractController
Route::get('/advertising-contracts', [AdvertisingContractController::class, 'index']);
Route::get('/advertising-contracts/{ma_quang_cao}/{ma_nqc}', [AdvertisingContractController::class, 'show']);
Route::post('/advertising-contracts', [AdvertisingContractController::class, 'store']);
Route::put('/advertising-contracts/{ma_quang_cao}/{ma_nqc}', [AdvertisingContractController::class, 'update']);
Route::delete('/advertising-contracts/{ma_quang_cao}/{ma_nqc}', [AdvertisingContractController::class, 'destroy']);

// Route VoucherController
Route::get('/vouchers', [VoucherController::class, 'index']);
Route::get('/vouchers/{id}', [VoucherController::class, 'show']);
Route::post('/vouchers', [VoucherController::class, 'store']);
Route::put('/vouchers/{id}', [VoucherController::class, 'update']);
Route::delete('/vouchers/{id}', [VoucherController::class, 'destroy']);

// Route VoucherRegisterController
Route::get('/voucher-registers', [VoucherRegisterController::class, 'index']);
Route::get('/voucher-registers/{ma_tk}/{ma_goi}', [VoucherRegisterController::class, 'show']);
Route::post('/voucher-registers', [VoucherRegisterController::class, 'store']);
Route::put('/voucher-registers/{ma_tk}/{ma_goi}', [VoucherRegisterController::class, 'update']);

// Route FunctionnController
Route::get('/functionns', [FunctionnController::class, 'index']);
Route::get('/functionns/{id}', [FunctionnController::class, 'show']);
Route::post('/functionns', [FunctionnController::class, 'store']);
Route::put('/functionns/{id}', [FunctionnController::class, 'update']);
Route::delete('/functionns/{id}', [FunctionnController::class, 'destroy']);

Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/accounts', [AccountController::class, 'index']);
Route::get('/accounts/{ma_tk}', [AccountController::class, 'show']);
Route::post('/accounts', [AccountController::class, 'store']);
Route::put('/accounts/{ma_tk}', [AccountController::class, 'update']);
Route::delete('/accounts/{ma_tk}', [AccountController::class, 'destroy']);

// Route UserController
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{ma_tk}', [UserController::class, 'show']);
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

// Route CommentController
Route::get('/comments', [CommentController::class, 'index']);
Route::get('/comment/{ma_tb}', [CommentController::class, 'show']);
Route::post('/comment', [CommentController::class, 'store']);

// Route GenreController
Route::get('/genres', [GenreController::class, 'getListOfGenres']); // Liệt kê danh sách thể loại
Route::get('/genres/songs', [GenreController::class, 'getListOfSongsInGenre']); // Liệt kê danh sách bài hát theo từng thể loại
Route::get('/genre/{ma_tb}', [GenreController::class, 'show']); // Tìm kiếm theo mã thể loại
Route::post('/genre', [GenreController::class, 'store']); // Thêm thể loại

// Route LikeSongController
// Route GenreSongController

Route::apiResource('song-likes', LikeSongController::class);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

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
