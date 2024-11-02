<?php

namespace App\Http\Controllers;

use App\Models\AlbumModel;
// use App\Models\SongModel;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

/*
    Album co 3 trang thai
    1: dang hoat dong
    2: dang cho duyet
    0: da xoa
*/

class AlbumController extends Controller
{

    //admin muon xem toan bo danh sach album
    public function index()
    {
        $albums = DB::table('albums')
            ->join('album_tai_khoan', 'albums.ma_album', '=', 'album_tai_khoan.ma_album')
            ->join('tai_khoan', 'album_tai_khoan.ma_tk', '=', 'tai_khoan.ma_tk')
            ->join('artists', 'tai_khoan.ma_artist', '=', 'artists.ma_artist')
            ->select('albums.*', 'artists.ten_artist')
            ->get();

        if ($albums->isEmpty()) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Album is empty'
            ], Response::HTTP_NOT_FOUND);
        } else {
            return response()->json([
                'albums' => $albums->map(function ($album) {
                    return [
                        'ma_album' => $album->ma_album,
                        'ten_album' => $album->ten_album,
                        'ngay_tao' => $album->ngay_tao,
                        'hinh_anh' => $album->hinh_anh,
                        'luot_yeu_thich' => $album->luot_yeu_thich,
                        'trang_thai' => $album->trang_thai,
                        'so_luong_bai_hat' => $album->so_luong_bai_hat,
                        'nguoi_so_huu' => $album->ten_artist,
                    ];
                }),
                'message' => 'Get all albums successfully',
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        }
    }

    // Lưu trữ dữ liệu
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ten_album' => 'required',
            'hinh_anh' => 'required',
        ]);
        // ?? ngafy tao nho lam nha , trang thai mac dinh, so bai hat thi cu truy van di 
        if ($validator->fails()) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            // Tạo mã không trùng lắp với csdl
            do {
                $date = now()->format('dmY');
                $uniqueNumber = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
                $ma_album = 'ALBUM' . $date . $uniqueNumber;
            } while (AlbumModel::where('ma_album', $ma_album)->exists());

            AlbumModel::create([
                'ma_album' => $ma_album,
                'ten_album' => $request->ten_album,
                'ngay_tao' => now(),
                'hinh_anh' => $request->hinh_anh,
                'luot_yeu_thich' => 0,
                'trang_thai' => 2,
                'so_luong_bai_hat' => 0,
            ]);

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Album created successfully',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Album created failed: ' . $e->getMessage());
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Album created failed'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // chưa có clone về lại nên cmt tạm
    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'ten_album' => 'required',
    //         'hinh_anh' => 'required',
    //         'songs' => 'array', // Kiểm tra xem có danh sách bài hát không
    //         'songs.*.ten_bai_hat' => 'required|string',
    //         'songs.*.thoi_luong' => 'required|integer',
    //         'songs.*.link_bai_hat' => 'required|string',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => Response::HTTP_BAD_REQUEST,
    //             'success' => false,
    //             'message' => 'Validation error',
    //             'errors' => $validator->errors()
    //         ], Response::HTTP_BAD_REQUEST);
    //     }

    //     try {
    //         do {
    //             $date = now()->format('dmY');
    //             $uniqueNumber = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
    //             $ma_album = 'ALBUM' . $date . $uniqueNumber;
    //         } while (AlbumModel::where('ma_album', $ma_album)->exists());

    //         $album = AlbumModel::create([
    //             'ma_album' => $ma_album,
    //             'ten_album' => $request->ten_album,
    //             'ngay_tao' => now(),
    //             'hinh_anh' => $request->hinh_anh,
    //             'luot_yeu_thich' => 0,
    //             'trang_thai' => 2,
    //             'so_luong_bai_hat' => 0,
    //         ]);

    //         if ($request->has('songs')) {
    //             foreach ($request->songs as $song) {
    //                 SongModel::create([
    //                     'ma_bai_hat' => 'SONG' . $date . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT),
    //                     'ten_bai_hat' => $song['ten_bai_hat'],
    //                     'thoi_luong' => $song['thoi_luong'],
    //                     'link_bai_hat' => $song['link_bai_hat'],
    //                     'ma_album' => $ma_album,
    //                     'ngay_phat_hanh' => now(),
    //                     'trang_thai' => 1,
    //                     // Thêm các trường khác của bài hát nếu cần
    //                 ]);
    //             }
    //             $album->so_luong_bai_hat = count($request->songs);
    //             $album->save();
    //         }

    //         return response()->json([
    //             'status' => Response::HTTP_OK,
    //             'message' => 'Album created successfully',
    //         ], Response::HTTP_OK);
    //     } catch (\Exception $e) {
    //         Log::error('Album created failed: ' . $e->getMessage());
    //         return response()->json([
    //             'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
    //             'message' => 'Album created failed'
    //         ], Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }

    public function show($ma_album)
    { // ???
        $account = AlbumModel::where('ma_album', $ma_album)->first();
        if ($account) {
            return response()->json([
                'status' => Response::HTTP_OK,
                'data' => [
                    'ma_album' => $account->ma_album,
                    'ten_album' => $account->ten_album,
                    'ngay_tao' => $account->ngay_tao,
                    'hinh_anh' => $account->hinh_anh,
                    'luot_yeu_thich' => $account->luot_yeu_thich,
                    'trang_thai' => $account->trang_thai,
                    'so_luong_bai_hat' => $account->so_luong_bai_hat,
                ],
            ], response::HTTP_OK);
        } else {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Account not found',
            ], response::HTTP_NOT_FOUND);
        }
    }

    public function update(Request $request, $ma_album)
    {
        $album = AlbumModel::find($ma_album);
        if (!$album) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'album not found',
            ], Response::HTTP_NOT_FOUND);
        }
        $validator = Validator::make($request->all(), [
            'ten_album' => 'required',
            'ngay_tao' => 'required',
            'hinh_anh' => 'required',
            'luot_yeu_thich' => 'required',
            'trang_thai' => 'required|integer|in:0,1,2,3',
            'so_luong_bai_hat' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $album->update([
                'ten_album' => $request->ten_album,
                'ngay_tao' => $request->ngay_tao,
                'hinh_anh' => $request->hinh_anh,
                'luot_yeu_thich' => $request->luot_yeu_thich,
                'trang_thai' => $request->trang_thai,
                'so_luong_bai_hat' => $request->so_luong_bai_hat,
            ]);

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Album updated successfully',
            ]);
        } catch (\Exception $e) {
            Log::error('Album update failed: ' . $e->getMessage());
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Album update failed'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($ma_album) // co nen la vay ko 
    {
        $album = AlbumModel::find($ma_album);
        if (!$album) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Album not found',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $album->trang_thai = 0;
            $album->save();

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Album status updated to deleted successfully',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Album status update failed: ' . $e->getMessage());
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Album status update failed'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // xem xet lai bao mat o ham nay hmmm
    public function getAlbumsByArtistAccount($ma_tk)
    {
        try {
            $albums = DB::table('album_tai_khoan')
                ->join('album', 'album_tai_khoan.ma_album', '=', 'album.ma_album')
                ->where('album_tai_khoan.ma_tk', $ma_tk)
                ->whereIn('album.trang_thai', [1, 2])
                ->select('album.*')
                ->get();
            if ($albums->isEmpty()) {
                return response()->json([
                    'status' => Response::HTTP_NOT_FOUND,
                    'message' => 'Album is empty'
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json([
                    'albums' => $albums->map(function ($album) {
                        return [
                            'ma_album' => $album->ma_album,
                            'ten_album' => $album->ten_album,
                            'ngay_tao' => $album->ngay_tao,
                            'hinh_anh' => $album->hinh_anh,
                            'luot_yeu_thich' => $album->luot_yeu_thich,
                            'trang_thai' => $album->trang_thai,
                            'so_luong_bai_hat' => $album->so_luong_bai_hat,
                        ];
                    }),
                    'message' => 'Get all albums successfully',
                    'status' => Response::HTTP_OK,
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Get albums by artist account failed'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getSongsInAlbum($ma_album)
    {
        try {
            $songs = DB::table('album')
                ->join('bai_hat', 'bai_hat.ma_album', '=', 'album.ma_album')
                ->where('album.ma_album', $ma_album)
                ->select('song.*')
                ->get();

            if ($songs->isEmpty()) {
                return response()->json([
                    'status' => Response::HTTP_NOT_FOUND,
                    'message' => 'Song is empty'
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json([
                    'songs' => $songs->map(function ($song) {
                        return [
                            'ma_bai_hat' => $song->ma_bai_hat,
                            'ten_bai_hat' => $song->ten_bai_hat,
                            'thoi_luong' => $song->thoi_luong,
                            'trang_thai' => $song->trang_thai,
                            'luot_nghe' => $song->luot_nghe,
                            'hinh_anh' => $song->hinh_anh,
                            'ma_album' => $song->ma_album,
                            'link_bai_hat' => $song->link_bai_hat,
                            'ngay_phat_hanh' => $song->ngay_phat_hanh,
                            'ma_artist' => $song->ma_artist,
                            'ma_phi_luot_nghe' => $song->ma_phi_luot_nghe,
                            'doanh_thu' => $song->doang_thu
                        ];
                    }),
                    'message' => 'Get all songs successfully',
                    'status' => Response::HTTP_OK,
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Get songs in album failed'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function likeAlbum(Request $request, $ma_album)
    {
        $ma_tk = $request->input('ma_tk');

        $album = AlbumModel::find($ma_album);
        if (!$album) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Album not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $like = DB::table('luot_thich_album')
            ->where('ma_album', $ma_album)
            ->where('ma_tk', $ma_tk)
            ->first();  // kiem tra xem da like chua

        try {
            if (!$like) {  // neu chua like truoc do 
                DB::table('luot_thich_album')->insert([
                    'ma_album' => $ma_album,
                    'ma_tk' => $ma_tk,
                    'ngay_tao' => now(),
                    'ngay_chinh_sua' => now(),
                    'ngay_huy' => null,
                ]);

                $album->increment('luot_thich_album');

                return response()->json([
                    'status' => Response::HTTP_OK,
                    'message' => 'Album liked successfully',
                ], Response::HTTP_OK);
            } elseif ($like && $like->ngay_huy !== null) { // thich lai
                DB::table('luot_thich_album')
                    ->where('ma_album', $ma_album)
                    ->where('ma_tk', $ma_tk)
                    ->update([
                        'ngay_huy' => null,
                        'ngay_chinh_sua' => now(),
                    ]);

                $album->increment('luot_thich_album'); // tang z on ko 

                return response()->json([
                    'status' => Response::HTTP_OK,
                    'message' => 'Album liked again successfully',
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => Response::HTTP_CONFLICT,
                    'message' => 'Album already liked by this account',
                ], Response::HTTP_CONFLICT);
            }
        } catch (\Exception $e) {
            Log::error('Album like failed: ' . $e->getMessage());
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Album like failed',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function unlikeAlbum(Request $request, $ma_album)
    {
        $ma_tk = $request->input('ma_tk');

        $album = AlbumModel::find($ma_album);
        if (!$album) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Album not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $like = DB::table('luot_thich_album')
            ->where('ma_album', $ma_album)
            ->where('ma_tk', $ma_tk)
            ->first(); // da thich album nay chua

        if (!$like || $like->ngay_huy !== null) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Album not liked by this account',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            DB::table('luot_thich_album')
                ->where('ma_album', $ma_album)
                ->where('ma_tk', $ma_tk)
                ->update([
                    'ngay_huy' => now(),
                    'ngay_chinh_sua' => now(),
                ]);

            if ($album->luot_thich_album > 0) {
                $album->decrement('luot_thich_album');
            }

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Album unliked successfully',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Album unlike failed: ' . $e->getMessage());
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Album unlike failed',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // chưa có clone về lại nên cmt tạm
    // public function addSongsToAlbum(Request $request, $ma_album)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'songs' => 'required|array', // Kiểm tra xem có danh sách bài hát không
    //         'songs.*.ma_bai_hat' => 'required|string', // Mã bài hát cần thiết để cập nhật
    //         // Các trường khác nếu cần
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => Response::HTTP_BAD_REQUEST,
    //             'success' => false,
    //             'message' => 'Validation error',
    //             'errors' => $validator->errors()
    //         ], Response::HTTP_BAD_REQUEST);
    //     }

    //     // Kiểm tra xem album có tồn tại hay không
    //     $album = AlbumModel::find($ma_album);
    //     if (!$album) {
    //         return response()->json([
    //             'status' => Response::HTTP_NOT_FOUND,
    //             'message' => 'Album not found',
    //         ], Response::HTTP_NOT_FOUND);
    //     }

    //     try {
    //         // Thêm bài hát vào album
    //         foreach ($request->songs as $song) {
    //             $songModel = SongModel::find($song['ma_bai_hat']);
    //             if ($songModel) {
    //                 $songModel->ma_album = $ma_album;
    //                 $songModel->save();
    //             }
    //         }

    //         $album->so_luong_bai_hat += count($request->songs);
    //         $album->save();

    //         return response()->json([
    //             'status' => Response::HTTP_OK,
    //             'message' => 'Songs added to album successfully',
    //         ], Response::HTTP_OK);
    //     } catch (\Exception $e) {
    //         Log::error('Adding songs to album failed: ' . $e->getMessage());
    //         return response()->json([
    //             'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
    //             'message' => 'Adding songs to album failed'
    //         ], Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }


    // search cho admin
    public function searchForAdmin(Request $request)
    {
        
        $query = DB::table('album')
            ->join('album_tai_khoan', 'album.ma_album', '=', 'album_tai_khoan.ma_album')
            ->join('tai_khoan', 'album_tai_khoan.ma_tk', '=', 'tai_khoan.ma_tk')
            ->join('artists', 'tai_khoan.ma_artist', '=', 'artists.ma_artist')
            ->select('albums.*', 'artists.ten_artist');

        // Tìm kiếm theo từ khóa trong tên tài khoản
        if ($request->has('keyword')) {
            $keyword = $request->input('keyword');
            $query->where('artists.ten_artist', 'like', '%' . $keyword . '%');
        }

        // Tìm kiếm theo từ khóa theo tên album
        if ($request->has('keyword')) {
            $keyword = $request->input('keyword');
            $query->where('album.ten_album', 'like', '%' . $keyword . '%');
        }

        // Lọc theo trạng thái
        if ($request->has('trang_thai')) {
            $trang_thai = $request->input('trang_thai');
            $query->where('albums.trang_thai', $trang_thai);
        }

        // Lọc theo ngày phát hành
        if ($request->has('ngay_phat_hanh')) {
            $ngay_phat_hanh = $request->input('ngay_phat_hanh');
            $query->whereDate('albums.ngay_tao', $ngay_phat_hanh);
        }

        try {
            $albums = $query->get();

            if ($albums->isEmpty()) {
                return response()->json([
                    'status' => Response::HTTP_NOT_FOUND,
                    'message' => 'No albums found'
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json([
                    'albums' => $albums->map(function ($album) {
                        return [
                            'ma_album' => $album->ma_album,
                            'ten_album' => $album->ten_album,
                            'ngay_tao' => $album->ngay_tao,
                            'hinh_anh' => $album->hinh_anh,
                            'luot_yeu_thich' => $album->luot_yeu_thich,
                            'trang_thai' => $album->trang_thai,
                            'so_luong_bai_hat' => $album->so_luong_bai_hat,
                            'nguoi_so_huu' => $album->ten_artist,
                        ];
                    }),
                    'message' => 'Albums found successfully',
                    'status' => Response::HTTP_OK,
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Search failed'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function searchForArtist(Request $request, $ma_tk)
    {
        
        $query = DB::table('album')
            ->where('album_tai_khoan.ma_tk', $ma_tk)
            ->select('albums.*');

        // Tìm kiếm theo từ khóa theo tên album
        if ($request->has('keyword')) {
            $keyword = $request->input('keyword');
            $query->where('album.ten_album', 'like', '%' . $keyword . '%');
        }

        // Lọc theo trạng thái
        if ($request->has('trang_thai')) {
            $trang_thai = $request->input('trang_thai');
            $query->where('albums.trang_thai', $trang_thai);
        }

        // Lọc theo ngày phát hành
        if ($request->has('ngay_phat_hanh')) {
            $ngay_phat_hanh = $request->input('ngay_phat_hanh');
            $query->whereDate('albums.ngay_tao', $ngay_phat_hanh);
        }

        try {
            $albums = $query->get();

            if ($albums->isEmpty()) {
                return response()->json([
                    'status' => Response::HTTP_NOT_FOUND,
                    'message' => 'No albums found'
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json([
                    'albums' => $albums->map(function ($album) {
                        return [
                            'ma_album' => $album->ma_album,
                            'ten_album' => $album->ten_album,
                            'ngay_tao' => $album->ngay_tao,
                            'hinh_anh' => $album->hinh_anh,
                            'luot_yeu_thich' => $album->luot_yeu_thich,
                            'trang_thai' => $album->trang_thai,
                            'so_luong_bai_hat' => $album->so_luong_bai_hat,
                        ];
                    }),
                    'message' => 'Albums found successfully',
                    'status' => Response::HTTP_OK,
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Search failed'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
