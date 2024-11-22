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
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\FunctionalDetailController;

// Route SongController
Route::get('/songs', [SongController::class, 'renderListOfSongs']); // Liệt kê danh sách bài hát trên trang chủ
Route::get('/songs/likes', [SongController::class, 'renderListOfLikesForSong']); // Liệt kê danh sách bài hát có lượt yêu thích trên trang chủ
Route::get('/songs/collab', [SongController::class, 'renderListOfSongsWithCollabArtist']); // Liệt kê danh sách bài hát có subartist
Route::get('/songs/artists', [SongController::class, 'renderListOfArtists']); // Liệt kê danh sách nghệ sĩ
Route::get('/songs/artist', [SongController::class, 'renderListOfSongsByArtist']); // Liệt kê danh sách bài hát theo từng nghệ sĩ
Route::get('/songs/artist/{ma_tai_khoan}', [SongController::class, 'renderListOfSongsBySearchedArtist']); // Liệt kê danh sách bài hát theo từng nghệ sĩ
Route::get('/song/{ma_bai_hat}', [SongController::class, 'renderSongDetails']); // Tìm kiếm bài hát theo mã bài hát
Route::post('/song', [SongController::class, 'store']); // Thêm một bài hát
Route::put('/song/{ma_bai_hat}', [SongController::class, 'update']); // Chỉnh sửa bài hát dựa vào mã bài hát
Route::delete('/song/{ma_bai_hat}', [SongController::class, 'destroy']); // Chỉnh sửa trạng thái bài hát dựa vào mã bài hát

// Route Playlist
Route::get('/playlist/{ma_tk}', [PlaylistController::class, 'renderListOfPlaylists']); // Liệt kê danh sách playlist cuả tài khoản 
Route::get('/playlist/{ma_tk}/{ma_playlist}', [PlaylistController::class, 'renderPlaylistByAccount']); // Liệt kê playlist theo mã tk, mã playlist
Route::post('/playlist', [PlaylistController::class, 'store']); // Thêm playlist
Route::delete('/playlist/{ma_tai_khoan}/{ma_playlist}', [PlaylistController::class, 'deletePlaylist']); // Xóa toàn bộ playlist
Route::delete('/playlist/{ma_tai_khoan}/{ma_playlist}/{ma_bai_hat}', [PlaylistController::class, 'deleteSongFromPlaylist']); //Xóa một bài hát trong playlist của một tài khoản

// Rote Album
Route::get('/albums/list-albums', [App\Http\Controllers\AlbumController::class, 'index']);
Route::post('/albums/like', [App\Http\Controllers\AlbumController::class, 'likeAlbum']);
Route::delete('/albums/unlike', [App\Http\Controllers\AlbumController::class, 'unlikeAlbum']);
Route::post('/albums/{ma_tk}', [App\Http\Controllers\AlbumController::class, 'store'])->where('ma_album', 'ACC\d{4}');
Route::put('/albums/{ma_album}/add', [App\Http\Controllers\AlbumController::class, 'addSongsToAlbum']);
Route::get('/albums/{ma_album}', [App\Http\Controllers\AlbumController::class, 'show'])->where('ma_album', 'AL\d{4}');
Route::put('/albums/{ma_album}', [App\Http\Controllers\AlbumController::class, 'update']);
Route::delete('/albums/{ma_album}', [App\Http\Controllers\AlbumController::class, 'destroy']);
Route::get('/albums/artist/{ma_tk}', [App\Http\Controllers\AlbumController::class, 'getAlbumsByArtistAccount']);
Route::get('/albums{ma_album}/songs', [App\Http\Controllers\AlbumController::class, 'getSongsInAlbum']);

//Route LikeAlbumController
Route::get('/albums-likes', [App\Http\Controllers\LikeAlbumController::class, 'index']);
Route::get('/albums-likes/{ma_tk}', [App\Http\Controllers\LikeAlbumController::class, 'accountLikesAlbums']);

// Route AdvertisementController
Route::get('/advertisements', [AdvertisementController::class, 'index']);
Route::get('/advertisements/{id}', [AdvertisementController::class, 'show']);
Route::post('/advertisements', [AdvertisementController::class, 'store']);
Route::put('/advertisements/{id}', [AdvertisementController::class, 'update']);
Route::delete('/advertisements/{id}', [AdvertisementController::class, 'destroy']);
Route::put('/advertisements/{id}/use', [AdvertisementController::class, 'useAdvertisement']);

// Route AdvertiserController
Route::get('/advertisers', [AdvertiserController::class, 'index']);
Route::get('/advertisers/{id}', [AdvertiserController::class, 'show']);
Route::post('/advertisers', [AdvertiserController::class, 'store']);
Route::put('/advertisers/{id}', [AdvertiserController::class, 'update']);
Route::delete('/advertisers/{id}', [AdvertiserController::class, 'destroy']);

// Route AdvertisingContractController
Route::get('/advertising-contracts', [AdvertisingContractController::class, 'index']);
Route::get('/advertising-contracts/{ma_hop_dong}', [AdvertisingContractController::class, 'show']);
Route::post('/advertising-contracts', [AdvertisingContractController::class, 'store']);
Route::put('/advertising-contracts/{ma_quang_cao}/{ma_nqc}', [AdvertisingContractController::class, 'update']);
Route::delete('/advertising-contracts/{ma_quang_cao}/{ma_nqc}', [AdvertisingContractController::class, 'destroy']);

// Route VoucherController
Route::get('/vouchers', [VoucherController::class, 'renderListOfVouchers']);
Route::get('/vouchers/registers', [VoucherController::class, 'renderVoucherRegister']);
Route::get('/vouchers/{id}', [VoucherController::class, 'show']);
Route::post('/vouchers', [VoucherController::class, 'store']);
Route::put('/vouchers/{id}', [VoucherController::class, 'update']);
Route::delete('/vouchers/{id}', [VoucherController::class, 'destroy']);

Route::get('/voucher/registers/{ma_tk}/{ma_goi}', [VoucherRegisterController::class, 'show']);
Route::post('/voucher/registers', [VoucherRegisterController::class, 'store']);
Route::put('/voucher/registers/{ma_tk}/{ma_goi}', [VoucherRegisterController::class, 'update']);

// Route FunctionnController
Route::get('/functionns', [FunctionnController::class, 'index']);
Route::get('/functionns/{id}', [FunctionnController::class, 'show']);
Route::post('/functionns', [FunctionnController::class, 'store']);
Route::put('/functionns/{id}', [FunctionnController::class, 'update']);
Route::delete('/functionns/{id}', [FunctionnController::class, 'destroy']);

// -------------

Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/login', [AuthController::class, 'login']);

//image
Route::post('/upload-image', [ImageUploadController::class, 'uploadImage']);

//account
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
Route::put('/updateNameDecentralization/{ma_phan_quyen}', [DecentralizationController::class, 'updateTenQuyenHan']);
// Route FunctionController
Route::get('/functionns', [FunctionnController::class, 'index']);
Route::get('/functionns/{ma_chuc_nang}', [FunctionnController::class, 'show']);
Route::post('/functionns', [FunctionnController::class, 'store']);
Route::put('/functionns/{ma_chuc_nang}', [FunctionnController::class, 'update']);
Route::delete('/functionns/{ma_chuc_nang}', [FunctionnController::class, 'destroy']);

// Route 
Route::put('/functionalDetail/update', [FunctionalDetailController::class, 'update']);
Route::delete('/functionalDetail/{ma_phan_quyen}/{ma_chuc_nang}', [FunctionalDetailController::class, 'delete']);
Route::get('/functional-details', [FunctionalDetailController::class, 'getFunctionalDetail']);
// Route NotificationController
Route::get('/notifications', [NotificationController::class, 'index']);
Route::get('/notifications/{ma_tb}', [NotificationController::class, 'show']);
Route::post('/notifications', [NotificationController::class, 'store']);

// Route CommentController
Route::get('/comments/song/{songId}', [CommentController::class, 'getCommentsBySong']);
Route::get('/comments', [CommentController::class, 'index']);
Route::get('/comments/{ma_bl}', [CommentController::class, 'show']);
Route::post('/comments', [CommentController::class, 'store']);
Route::put('/comments/{ma_bl}', [CommentController::class, 'update']);
Route::delete('/comments/{ma_bl}', [CommentController::class, 'destroy']);


// Route GenreController
Route::get('/genres', [GenreController::class, 'renderListOfGenres']); // Liệt kê danh sách thể loại
Route::get('/genre/songs/{ma_the_loai}', [GenreController::class, 'renderListOfSongsInGenre']); // Tìm kiếm các bài hát theo mã thể loại
Route::get('/genre/{ma_the_loai}', [GenreController::class, 'renderGenreDetails']); // Tìm kiếm theo mã thể loại
Route::post('/genre', [GenreController::class, 'store']); // Thêm thể loại
Route::delete('/genre/{ma_the_loai}', [GenreController::class, 'destroy']);

// Route LikeSongController

Route::get('/song-likes', [LikeSongController::class, 'index']);
Route::post('/song-likes', [LikeSongController::class, 'store']);
Route::delete('/song-likes', [LikeSongController::class, 'destroy']);
Route::get('/like-count/{ma_bai_hat}', [LikeSongController::class, 'getLikeCount']);

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
