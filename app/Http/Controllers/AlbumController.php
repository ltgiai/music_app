<?php

namespace App\Http\Controllers;

use App\Models\AlbumModel;
use App\Models\SongModel;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

/*
    Album co 5 trang thai
    0: ẩn 
    1: công khai
    2: chờ duyệt
    3: đã duyệt thành công
    4: đã xóa
    
    Dang tai bai truoc sau do moi tao album

    Sau khi tao album thi them bai hat vao album
    
    Co the them truc tiep bai hat vao trong luc tao album hoac sau khi tao album
*/

class AlbumController extends Controller
{
    //admin muon xem toan bo danh sach album
    public function index() //checked
    {
        $albums = DB::table('album')
            ->join('tai_khoan', 'album.ma_tk', '=', 'tai_khoan.ma_tk')
            ->join('user', 'tai_khoan.ma_tk', '=', 'user.ma_tk')
            ->select('album.*', 'user.ten_user as ten_artist')
            ->whereIn('album.trang_thai', [0, 1, 2, 3])
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

    // bài hát sẽ được tạo trước đó, không cần phải tạo mới
    public function store(Request $request, $ma_tk) //checked cho cả lúc chỉ tạo hoặc thêm n bài lúc tạo
    {
        $albumValidator = Validator::make($request->all(), [
            'ten_album' => 'required|string|max:255',
            'hinh_anh' => 'required|url',
        ]);

        if ($albumValidator->fails()) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Album validation error',
                'errors' => $albumValidator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($request->has('songs')) {
            $songsValidator = Validator::make($request->all(), [
                'songs' => 'sometimes|array',
                'songs.*.ma_bai_hat' => 'required|string',
                'songs.*.ten_bai_hat' => 'required|string|max:255',
                'songs.*.thoi_luong' => 'required|numeric',
                'songs.*.ngay_phat_hanh' => 'required|date',
                'songs.*.ma_album' => 'nullable|in:null',
            ]);

            if ($songsValidator->fails()) {
                return response()->json([
                    'status' => Response::HTTP_BAD_REQUEST,
                    'success' => false,
                    'message' => 'Songs validation error',
                    'errors' => $songsValidator->errors()
                ], Response::HTTP_BAD_REQUEST);
            }
        }

        try {
            do {
                $uniqueNumber = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
                $ma_album = 'AL' . $uniqueNumber;
            } while (AlbumModel::where('ma_album', $ma_album)->exists());

            $album = AlbumModel::create([
                'ma_tk' => $ma_tk,
                'ma_album' => $ma_album,
                'ten_album' => $request->ten_album,
                'ngay_tao' => now(),
                'hinh_anh' => $request->hinh_anh,
                'luot_yeu_thich' => 0,
                'trang_thai' => 2,
                'so_luong_bai_hat' => 0,
            ]);

            if ($request->has('songs') && is_array($request->songs)) {
                foreach ($request->songs as $song) {
                    $existingSong = SongModel::where('ma_bai_hat', $song['ma_bai_hat'])->first();
                    if ($existingSong && $existingSong->ma_album === null) {
                        $existingSong->ma_album = $ma_album;
                        $existingSong->save();
                    }
                }
                $album->so_luong_bai_hat = count($request->songs);
                $album->save();
            }

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Album created successfully, account = ' . $ma_tk,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Album creation failed: ' . $e->getMessage());
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Album creation failed',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, $ma_album)
    {
        $album = AlbumModel::find($ma_album);
        if (!$album) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Album not found',
            ], Response::HTTP_NOT_FOUND);
        }

        // Validate thông tin album
        if ($request->has('songs')) {
            $validator = Validator::make($request->all(), [
                'ten_album' => 'required',
                'hinh_anh' => 'required',
                'trang_thai' => 'required|integer|in:0,1,2,3',
                'songs' => 'sometimes|array',
                'songs.*.ma_bai_hat' => 'required|string',
                'songs.*.ten_bai_hat' => 'required|string',
                'songs.*.thoi_luong' => 'required|numeric',
                'songs.*.ngay_phat_hanh' => 'required|date',
                'songs.*.ma_album' => 'nullable|string',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => Response::HTTP_BAD_REQUEST,
                    'errors' => $validator->errors()
                ], Response::HTTP_BAD_REQUEST);
            }
        }
        try {
            $album->update([
                'ten_album' => $request->ten_album,
                'hinh_anh' => $request->hinh_anh,
                'trang_thai' => $request->trang_thai,
            ]);

            if ($request->has('songs') && is_array($request->songs)) {
                foreach ($request->songs as $song) {
                    $songModel = SongModel::where('ma_bai_hat', $song['ma_bai_hat'])->first();
                    if ($songModel) {
                        if ($song['ma_album'] === null) {
                            // Xóa bài hát khỏi album
                            if ($songModel->ma_album === $ma_album) {
                                $songModel->ma_album = null;
                                $songModel->save();
                            }
                        } else if ($song['ma_album'] === $ma_album) {
                            // Thêm bài hát vào album
                            if ($songModel->ma_album === null) {
                                $songModel->ma_album = $ma_album;
                                $songModel->save();
                            }
                        }
                    }
                }
                $album->so_luong_bai_hat = SongModel::where('ma_album', $ma_album)->count();
                $album->save();
            }
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Album and updated successfully',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Album update failed: ' . $e->getMessage());
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Album update failed. Error: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function show($ma_album) //checked
    {
        $album = DB::table('album')
            ->join('tai_khoan', 'album.ma_tk', '=', 'tai_khoan.ma_tk')
            ->join('user', 'tai_khoan.ma_tk', '=', 'user.ma_tk')
            ->select('album.*', 'user.ten_user as ten_artist')
            ->where('album.ma_album', $ma_album)
            ->first();
        if ($album) {
            return response()->json([
                'status' => Response::HTTP_OK,
                'data' => [
                    'ma_album' => $album->ma_album,
                    'ten_album' => $album->ten_album,
                    'ngay_tao' => $album->ngay_tao,
                    'hinh_anh' => $album->hinh_anh,
                    'luot_yeu_thich' => $album->luot_yeu_thich,
                    'trang_thai' => $album->trang_thai,
                    'so_luong_bai_hat' => $album->so_luong_bai_hat,
                    'nguoi_so_huu' => $album->ten_artist,
                ],
            ], response::HTTP_OK);
        } else {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Account not found',
            ], response::HTTP_NOT_FOUND);
        }
    }

    // set trang thai ve 4
    public function destroy($ma_album) //checked
    {
        $album = AlbumModel::find($ma_album);
        if (!$album) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Album not found',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $album->trang_thai = 4;
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

    // admin mới mò tới hàm này, cơ mà hàm này có cần không thì ko thấy
    public function getAlbumsByArtistAccount($ma_tk) //checked
    {
        try {
            $albums = DB::table('album')
                ->where('album.ma_tk', $ma_tk)
                ->whereIn('album.trang_thai', [0, 1, 2, 3])
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

    //chỉ hiển thị những thông tin cần thiết trong figma
    public function getSongsInAlbum($ma_album) //checked
    {
        try {
            $album = DB::table('album')
                ->join('user', 'album.ma_tk', '=', 'user.ma_tk')
                ->select('album.*', 'user.ten_user as ten_artist', 'user.ma_tk')
                ->where('album.ma_album', $ma_album)
                ->whereIn('album.trang_thai', [0, 1, 2, 3])
                ->first();

            if (!$album) {
                return response()->json([
                    'status' => Response::HTTP_NOT_FOUND,
                    'message' => 'Album not foundddd'
                ], Response::HTTP_NOT_FOUND);
            }

            $songs = DB::table('bai_hat')
                ->join('album', 'album.ma_album', '=', 'bai_hat.ma_album')
                ->join('theloai_baihat', 'bai_hat.ma_bai_hat', '=', 'theloai_baihat.ma_bai_hat')
                ->join('the_loai', 'the_loai.ma_the_loai', '=', 'theloai_baihat.ma_the_loai')
                ->where('bai_hat.ma_album', $ma_album)
                ->select('bai_hat.*', 'the_loai.ten_the_loai')
                ->whereIn('album.trang_thai', [0, 1, 2, 3])
                ->get();


            return response()->json([
                'album' => [
                    'ma_album' => $album->ma_album,
                    'ten_album' => $album->ten_album,
                    'ngay_tao' => $album->ngay_tao,
                    'hinh_anh' => $album->hinh_anh,
                    'luot_yeu_thich' => $album->luot_yeu_thich,
                    'trang_thai' => $album->trang_thai,
                    'so_luong_bai_hat' => $album->so_luong_bai_hat,
                    'nguoi_so_huu' => $album->ten_artist,
                    'ma_nguoi_so_huu' => $album->ma_tk,
                    'songs' => $songs->isEmpty() ? null : $songs->map(function ($song) {
                        return [
                            'ma_bai_hat' => $song->ma_bai_hat,
                            'ten_bai_hat' => $song->ten_bai_hat,
                            'thoi_luong' => $song->thoi_luong,
                            'trang_thai' => $song->trang_thai,
                            'luot_nghe' => $song->luot_nghe,
                            'hinh_anh' => $song->hinh_anh,
                            'ngay_phat_hanh' => $song->ngay_phat_hanh,
                            'the_loai' => $song->ten_the_loai,
                        ];
                    }),
                ],
                'message' => 'Get all songs successfully',
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Get songs in album failed'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // tách ra để đúng logic, không được bỏ chung với hàm update()
    // test ACC0009 thich album AL0001
    public function likeAlbum(Request $request)
    {
        $ma_tk = $request->input('ma_tk');
        $ma_album = $request->input('ma_album');

        if (!$ma_tk || !$ma_album) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Both ma_tk and ma_album are required',
            ], Response::HTTP_BAD_REQUEST);
        }

        $accountExists = DB::table('tai_khoan')->where('ma_tk', $ma_tk)->exists();
        if (!$accountExists) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Account not found',
            ], Response::HTTP_NOT_FOUND);
        }

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
            ->first();

        try {
            if (!$like) {
                DB::table('luot_thich_album')->insert([
                    'ma_album' => $ma_album,
                    'ma_tk' => $ma_tk,
                    'ngay_thich' => now(),
                ]);

                $album->increment('luot_yeu_thich');

                return response()->json([
                    'status' => Response::HTTP_OK,
                    'message' => 'Album liked successfully',
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
                'message' => 'Album like failed Error: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // tách ra để đúng logic, không được bỏ chung với hàm update()
    public function unlikeAlbum(Request $request)
    {
        $ma_tk = $request->input('ma_tk');
        $ma_album = $request->input('ma_album');

        if (!$ma_tk || !$ma_album) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Both ma_tk and ma_album are required',
            ], Response::HTTP_BAD_REQUEST);
        }

        $accountExists = DB::table('tai_khoan')->where('ma_tk', $ma_tk)->exists();
        if (!$accountExists) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Account not found',
            ], Response::HTTP_NOT_FOUND);
        }

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
            ->first();

        if (!$like) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Album not liked by this account',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            DB::table('luot_thich_album')
                ->where('ma_album', $ma_album)
                ->where('ma_tk', $ma_tk)
                ->delete();

            if ($album->luot_yeu_thich > 0) {
                $album->decrement('luot_yeu_thich');
            }

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Album unliked successfully',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Album unlike failed: ' . $e->getMessage());
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Album unlike failed Error: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAlbumsLikedByThisUser($ma_tk)
    {
        $albums = DB::table('luot_thich_album')
            ->join('album', 'luot_thich_album.ma_album', '=', 'album.ma_album')
            ->join('user', 'album.ma_tk', '=', 'user.ma_tk')
            ->select('luot_thich_album.ma_tk', 'album.*', 'user.ten_user as ten_artist')
            ->where('luot_thich_album.ma_tk', $ma_tk)
            ->where('album.trang_thai', 1)
            ->get();

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
    }

}
