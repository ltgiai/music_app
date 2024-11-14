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

// Route SongController
Route::prefix('songs')->group(function () {
    // Route để lấy tất cả bài hát
    Route::get('/', [SongController::class, 'index']);

    // Route để lấy chi tiết một bài hát theo mã bài hát
    Route::get('/{ma_bai_hat}', [SongController::class, 'show']);

    // Route để tạo mới một bài hát
    Route::post('/', [SongController::class, 'store']);

    // Route để cập nhật thông tin bài hát
    Route::put('/{ma_bai_hat}', [SongController::class, 'update']);

    // Route để xóa bài hát (cập nhật trạng thái thành đã xóa)
    Route::delete('/{ma_bai_hat}', [SongController::class, 'destroy']);
});

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

// Route::prefix('albums')->group(function () {
//     Route::get('/', [App\Http\Controllers\AlbumController::class, 'index']);  chua sua lai nha
// });

Route::prefix('/albums')->group(function () {
    Route::get('/list-albums', [App\Http\Controllers\AlbumController::class, 'index']); // http://127.0.0.1:8000/api/albums
    Route::post('/create', [App\Http\Controllers\AlbumController::class, 'store']); // đ biết route ở đâu @@
    Route::get('/detail-album/{ma_album}', [App\Http\Controllers\AlbumController::class, 'show']);
    Route::put('/update-album/{ma_album}', [App\Http\Controllers\AlbumController::class, 'update']);
    Route::delete('/delete-album/{ma_album}', [App\Http\Controllers\AlbumController::class, 'destroy']);
    Route::get('/all-artist-albums/{ma_tk}', [App\Http\Controllers\AlbumController::class, 'getAlbumsByArtistAccount']);
    Route::get('/detail-artist-album/{ma_album}', [App\Http\Controllers\AlbumController::class, 'getSongsInAlbum']);
    Route::post('/like-album/{ma_album}', [App\Http\Controllers\AlbumController::class, 'likeAlbum']);
    Route::post('/like-album/{ma_album}', [App\Http\Controllers\AlbumController::class, 'unlikeAlbum']);
    Route::get('/admin-search', [App\Http\Controllers\AlbumController::class, 'searchForAdmin']);
    Route::get('/artist-search/{ma_tk}', [App\Http\Controllers\AlbumController::class, 'searchForArtist']);
});

Route::apiResource('comments', CommentController::class);
Route::apiResource('song-likes', LikeSongController::class);
Route::apiResource('categories', SongCategoryController::class);
Route::apiResource('genres', GenreController::class);
Route::apiResource('genre-songs', GenreSongController::class);

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
