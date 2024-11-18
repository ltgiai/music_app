<?php

namespace App\Http\Controllers;

use App\Models\SongModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class SongController extends Controller
{
    public function renderListOfSongs()
    {
        $songs = DB::table('bai_hat')
            ->join('album', 'bai_hat.ma_album', '=', 'album.ma_album')
            ->join('tai_khoan', 'bai_hat.ma_tk_artist', '=', 'tai_khoan.ma_tk')
            ->join('user', 'tai_khoan.ma_tk', '=', 'user.ma_tk')
            ->join('theloai_baihat', 'bai_hat.ma_bai_hat', '=', 'theloai_baihat.ma_bai_hat')
            ->join('the_loai', 'the_loai.ma_the_loai', '=', 'theloai_baihat.ma_the_loai')
            ->join('chat_luong_bai_hat', 'bai_hat.ma_bai_hat', '=', 'chat_luong_bai_hat.ma_bai_hat')
            ->select('bai_hat.*', 'user.*', 'tai_khoan.*', 'album.*', 'theloai_baihat.*', 'the_loai.*', 'chat_luong_bai_hat.*')
            ->get();

        if ($songs->isEmpty()) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'ERROR 404'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'data' => $songs->map(function ($item) {
                return [
                    'ma_bai_hat' => $item->ma_bai_hat,
                    'ten_bai_hat' => $item->ten_bai_hat,
                    'album' => $item->ten_album,
                    'artist' => $item->ten_user,
                    'thoi_luong' => $item->thoi_luong,
                    'luot_nghe' => $item->luot_nghe,
                    'hinh_anh' => $item->hinh_anh,
                    'ngay_phat_hanh' => $item->ngay_phat_hanh,
                    'chat_luong' => $item->chat_luong,
                    'link_bai_hat' => $item->link_bai_hat,
                    'trang_thai' => $item->trang_thai
                ];
            }),
            'message' => 'Get all songs successfully',
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }

    public function renderListOfArtists()
    {
        $artists = DB::table('tai_khoan')
            ->join('user', 'user.ma_tk', '=', 'tai_khoan.ma_tk')
            ->join('phan_quyen', 'tai_khoan.ma_phan_quyen', '=', 'phan_quyen.ma_phan_quyen')
            ->select('tai_khoan.*', 'user.*')
            ->where('tai_khoan.ma_phan_quyen', 'AUTH0002') 
            ->get();

        if ($artists->isEmpty()) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'ERROR 404'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'data' => $artists->map(function ($item) {
                return [
                    'ma_artist' => $item->ma_tk,
                    'ten_artist' => $item->ten_user
                ];
            }),
            'message' => 'Get all artists successfully',
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }

    public function renderListOfSongsWithCollabArtist()
    {
        $songs = DB::table('bai_hat_subartist')
            ->join('bai_hat', 'bai_hat.ma_bai_hat', '=', 'bai_hat_subartist.ma_bai_hat')
            ->join('tai_khoan as subartist_tai_khoan', 'subartist_tai_khoan.ma_tk', '=', 'bai_hat_subartist.ma_subartist')
            ->join('tai_khoan as artist_tai_khoan', 'artist_tai_khoan.ma_tk', '=', 'bai_hat.ma_tk_artist')
            ->join('user as user_artist', 'user_artist.ma_tk', '=', 'artist_tai_khoan.ma_tk')
            ->join('user as user_subartist', 'user_subartist.ma_tk', '=', 'subartist_tai_khoan.ma_tk')
            ->select(
                'bai_hat.*',
                'user_artist.ten_user as ten_artist',
                'user_subartist.ten_user as ten_collab_artist'
            )
            ->get();

        if ($songs->isEmpty()) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'No songs with collaboration found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'data' => $songs->map(function ($song) {
                return [
                    'ma_bai_hat' => $song->ma_bai_hat,
                    'ten_bai_hat' => $song->ten_bai_hat,
                    'artist' => $song->ten_artist,
                    'collab_artist' => $song->ten_collab_artist,
                    'thoi_luong' => $song->thoi_luong,
                    'luot_nghe' => $song->luot_nghe,
                    'hinh_anh' => $song->hinh_anh,
                    'ngay_phat_hanh' => $song->ngay_phat_hanh,
                    'trang_thai' => $song->trang_thai,
                ];
            }),
            'message' => 'Get all songs with collab artist successfully',
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }

    // Hiển thị thông tin chi tiết của một bài hát
    public function renderSongDetails($ma_bai_hat)
    {
        // Tìm bài hát dựa vào mã bài hát
        $song = DB::table('bai_hat')
            ->leftJoin('album', 'bai_hat.ma_album', '=', 'album.ma_album')
            ->leftJoin('tai_khoan', 'bai_hat.ma_tk_artist', '=', 'tai_khoan.ma_tk')
            ->leftJoin('user', 'tai_khoan.ma_tk', '=', 'user.ma_tk')
            ->leftJoin('theloai_baihat', 'bai_hat.ma_bai_hat', '=', 'theloai_baihat.ma_bai_hat')
            ->leftJoin('the_loai', 'theloai_baihat.ma_the_loai', '=', 'the_loai.ma_the_loai')
            ->leftJoin('chat_luong_bai_hat', 'bai_hat.ma_bai_hat', '=', 'chat_luong_bai_hat.ma_bai_hat')
            ->select(
                'bai_hat.*',
                'album.ten_album',
                'user.ten_user as ten_artist',
                'the_loai.ten_the_loai',
                'chat_luong_bai_hat.chat_luong',
                'chat_luong_bai_hat.link_bai_hat'
            )
            ->where('bai_hat.ma_bai_hat', $ma_bai_hat)
            ->first();

        // Kiểm tra kết quả trả về
        if (!$song) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Song not found',
            ], Response::HTTP_NOT_FOUND);
        }

        // Trả về dữ liệu JSON
        return response()->json([
            'data' => [
                'ma_bai_hat' => $song->ma_bai_hat,
                'ten_bai_hat' => $song->ten_bai_hat,
                'album' => $song->ten_album,
                'artist' => $song->ten_artist,
                'the_loai' => $song->ten_the_loai,
                'thoi_luong' => $song->thoi_luong,
                'trang_thai' => $song->trang_thai,
                'luot_nghe' => $song->luot_nghe,
                'hinh_anh' => $song->hinh_anh,
                'ngay_phat_hanh' => $song->ngay_phat_hanh,
                'chat_luong' => $song->chat_luong,
                'link_bai_hat' => $song->link_bai_hat,
            ],
            'message' => 'Song details retrieved successfully',
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }



    // Lưu trữ một bài hát mới
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ten_bai_hat' => 'required',
            'thoi_luong' => 'required|numeric',
            'hinh_anh' => 'required',
            'ma_album' => 'required',
            'link_bai_hat' => 'required',
            'ngay_phat_hanh' => 'required|date',
            'ma_artist' => 'required',
            'ma_phi_luot_nghe' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'errors' => $validator->errors(),
                'message' => 'Validation error'
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            // Tạo mã bài hát duy nhất
            do {
                $date = now()->format('dmY');
                $uniqueNumber = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
                $ma_bai_hat = 'SONG-' . $date . $uniqueNumber;
            } while (SongModel::where('ma_bai_hat', $ma_bai_hat)->exists());

            SongModel::create([
                'ma_bai_hat' => $ma_bai_hat,
                'ten_bai_hat' => $request->ten_bai_hat,
                'thoi_luong' => $request->thoi_luong,
                'trang_thai' => 1, // mặc định là active
                'luot_nghe' => 0,
                'hinh_anh' => $request->hinh_anh,
                'ma_album' => $request->ma_album,
                'link_bai_hat' => $request->link_bai_hat,
                'ngay_phat_hanh' => $request->ngay_phat_hanh,
                'ma_artist' => $request->ma_artist,
                'ma_phi_luot_nghe' => $request->ma_phi_luot_nghe,
                'doanh_thu' => 0,
            ]);

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Song created successfully',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Song creation failed: ' . $e->getMessage());
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Song creation failed',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Cập nhật thông tin bài hát
    public function update(Request $request, $ma_bai_hat)
    {
        $song = SongModel::find($ma_bai_hat);
        if (!$song) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Song not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'ten_bai_hat' => 'required',
            'thoi_luong' => 'required|numeric',
            'hinh_anh' => 'required',
            'ma_album' => 'required',
            'link_bai_hat' => 'required',
            'ngay_phat_hanh' => 'required|date',
            'trang_thai' => 'required|integer|in:0,1',
            'luot_nghe' => 'required|integer',
            'doanh_thu' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'errors' => $validator->errors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $song->update([
                'ten_bai_hat' => $request->ten_bai_hat,
                'thoi_luong' => $request->thoi_luong,
                'hinh_anh' => $request->hinh_anh,
                'ma_album' => $request->ma_album,
                'link_bai_hat' => $request->link_bai_hat,
                'ngay_phat_hanh' => $request->ngay_phat_hanh,
                'trang_thai' => $request->trang_thai,
                'luot_nghe' => $request->luot_nghe,
                'doanh_thu' => $request->doanh_thu,
            ]);

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Song updated successfully',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Song update failed: ' . $e->getMessage());
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Song update failed',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Cập nhật trạng thái bài hát về đã xóa
    public function destroy($ma_bai_hat)
    {
        $song = SongModel::find($ma_bai_hat);
        if (!$song) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Song not found',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $song->trang_thai = 0; // cập nhật trạng thái là đã xóa
            $song->save();

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Song deleted successfully',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Song deletion failed: ' . $e->getMessage());
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Song deletion failed',
            ]);
        }
    }
}
