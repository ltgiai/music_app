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
            ->join('album as song_album', 'bai_hat.ma_album', '=', 'song_album.ma_album')
            ->join('tai_khoan', 'bai_hat.ma_tk_artist', '=', 'tai_khoan.ma_tk')
            ->join('user', 'tai_khoan.ma_tk', '=', 'user.ma_tk')
            ->join('theloai_baihat', 'bai_hat.ma_bai_hat', '=', 'theloai_baihat.ma_bai_hat')
            ->join('the_loai', 'the_loai.ma_the_loai', '=', 'theloai_baihat.ma_the_loai')
            ->join('chat_luong_bai_hat', 'bai_hat.ma_bai_hat', '=', 'chat_luong_bai_hat.ma_bai_hat')
            ->select(
                'bai_hat.*',
                'bai_hat.hinh_anh as bai_hat_hinh_anh', // Alias cho hinh_anh của bảng bai_hat
                'song_album.ten_album as album_name',
                'user.ten_user as artist_name',
                'chat_luong_bai_hat.*'
            )
            ->get();

        if ($songs->isEmpty()) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'ERROR 404 - No songs found'
            ], Response::HTTP_NOT_FOUND);
        }

        $formattedSongs = $songs->map(function ($item) {
            return [
                'ma_bai_hat' => $item->ma_bai_hat,
                'ten_bai_hat' => $item->ten_bai_hat,
                'album' => $item->album_name, // Alias từ bảng album
                'artist' => $item->artist_name, // Alias từ bảng user
                'thoi_luong' => $item->thoi_luong,
                'luot_nghe' => $item->luot_nghe,
                'hinh_anh' => $item->bai_hat_hinh_anh, // Sử dụng alias của hinh_anh từ bảng bai_hat
                'ngay_phat_hanh' => $item->ngay_phat_hanh,
                'chat_luong' => $item->chat_luong,
                'link_bai_hat' => $item->link_bai_hat,
                'trang_thai' => $item->trang_thai
            ];
        });

        return response()->json([
            'data' => $formattedSongs,
            'message' => 'Get all songs successfully',
            'status' => Response::HTTP_OK
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

    public function renderListOfSongsByArtist()
    {
        // Lấy danh sách nghệ sĩ và bài hát của họ
        $artists = DB::table('tai_khoan')
            ->join('user', 'user.ma_tk', '=', 'tai_khoan.ma_tk')
            ->join('phan_quyen', 'tai_khoan.ma_phan_quyen', '=', 'phan_quyen.ma_phan_quyen')
            ->join('bai_hat', 'bai_hat.ma_tk_artist', '=', 'tai_khoan.ma_tk')
            ->join('album', 'album.ma_album', '=', 'bai_hat.ma_album')
            ->select(
                'tai_khoan.*',
                'user.*',
                'bai_hat.*',
                'bai_hat.hinh_anh as song_image',
                'album.*',
                'album.hinh_anh as album_image'
            )
            ->where('tai_khoan.ma_phan_quyen', 'AUTH0002') // Chỉ lấy tài khoản có quyền nghệ sĩ
            ->get()
            ->groupBy('ma_tk'); // Nhóm dữ liệu theo mã tài khoản (nghệ sĩ)

        // Kiểm tra nếu không tìm thấy nghệ sĩ nào
        if ($artists->isEmpty()) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Không tìm thấy nghệ sĩ và bài hát nào',
            ], Response::HTTP_NOT_FOUND);
        }

        // Chuẩn bị dữ liệu trả về
        $data = $artists->map(function ($songs, $ma_artist) {
            $artistInfo = $songs->first(); // Lấy thông tin cơ bản của nghệ sĩ
            return [
                'ma_artist' => $ma_artist,
                'ten_artist' => $artistInfo->ten_user,
                'bai_hat' => $songs->map(function ($song) {
                    return [
                        'ma_bai_hat' => $song->ma_bai_hat,
                        'ten_bai_hat' => $song->ten_bai_hat,
                        'thoi_luong' => $song->thoi_luong,
                        'ngay_phat_hanh' => $song->ngay_phat_hanh,
                        'trang_thai' => $song->trang_thai,
                        'doanh_thu' => $song->doanh_thu,
                        'luot_nghe' => $song->luot_nghe,
                        'hinh_anh' => $song->song_image,
                        'album' => $song->ten_album
                    ];
                })->values(),
            ];
        })->values();

        // Trả về kết quả
        return response()->json([
            'data' => $data,
            'message' => 'Lấy danh sách nghệ sĩ và bài hát thành công',
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }



    public function renderListOfSongsBySearchedArtist($ma_artist)
    {
        // Lấy danh sách nghệ sĩ và bài hát của họ
        $artists = DB::table('tai_khoan')
            ->join('user', 'user.ma_tk', '=', 'tai_khoan.ma_tk')
            ->join('phan_quyen', 'tai_khoan.ma_phan_quyen', '=', 'phan_quyen.ma_phan_quyen')
            ->join('bai_hat', 'bai_hat.ma_tk_artist', '=', 'tai_khoan.ma_tk')
            ->join('album', 'album.ma_album', '=', 'bai_hat.ma_album')
            ->select(
                'tai_khoan.*',
                'user.*',
                'bai_hat.*',
                'bai_hat.hinh_anh as song_image',
                'album.*',
                'album.hinh_anh as album_image'
            )
            ->where('tai_khoan.ma_phan_quyen', 'AUTH0002') // Chỉ lấy tài khoản có quyền nghệ sĩ
            ->where('tai_khoan.ma_tk', '=', $ma_artist)
            ->get()
            ->groupBy('ma_tk'); // Nhóm dữ liệu theo mã tài khoản (nghệ sĩ)

        // Kiểm tra nếu không tìm thấy nghệ sĩ nào
        if ($artists->isEmpty()) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Không tìm thấy nghệ sĩ và bài hát nào',
            ], Response::HTTP_NOT_FOUND);
        }

        // Chuẩn bị dữ liệu trả về
        $data = $artists->map(function ($songs, $ma_artist) {
            $artistInfo = $songs->first(); // Lấy thông tin cơ bản của nghệ sĩ
            return [
                'ma_artist' => $ma_artist,
                'ten_artist' => $artistInfo->ten_user,
                'bai_hat' => $songs->map(function ($song) {
                    return [
                        'ma_bai_hat' => $song->ma_bai_hat,
                        'ten_bai_hat' => $song->ten_bai_hat,
                        'thoi_luong' => $song->thoi_luong,
                        'ngay_phat_hanh' => $song->ngay_phat_hanh,
                        'trang_thai' => $song->trang_thai,
                        'doanh_thu' => $song->doanh_thu,
                        'luot_nghe' => $song->luot_nghe,
                        'hinh_anh' => $song->song_image,
                        'album' => $song->ten_album,
                    ];
                })->values(),
            ];
        })->values();

        // Trả về kết quả
        return response()->json([
            'data' => $data,
            'message' => 'Lấy danh sách nghệ sĩ và bài hát thành công',
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
        // Xác thực dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'ten_bai_hat' => 'required',
            'thoi_luong' => 'required|numeric',
            'hinh_anh' => 'required',
            'ma_album' => 'nullable|exists:album,ma_album', // Nếu có, phải tồn tại trong bảng album
            // 'link_bai_hat' => 'required',
            'ngay_phat_hanh' => 'required|date',
            'ma_tk_artist' => 'required|exists:tai_khoan,ma_tk', // Bắt buộc, phải tồn tại trong bảng artist
            'ma_the_loai' => 'required|exists:the_loai,ma_the_loai', // Bắt buộc, phải tồn tại trong bảng thể loại
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'errors' => $validator->errors(),
                'message' => 'Validation error',
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            // Tạo mã bài hát duy nhất với định dạng BHxxxx
            $lastSong = SongModel::latest('ma_bai_hat')->first();
            $lastNumber = $lastSong ? (int)substr($lastSong->ma_bai_hat, 2) : 0;
            $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
            $ma_bai_hat = 'BH' . $newNumber;

            // Kiểm tra mã album (nếu có) và mã tài khoản nghệ sĩ
            if ($request->ma_album) {
                $albumExists = DB::table('album')->where('ma_album', $request->ma_album)->exists();
                if (!$albumExists) {
                    return response()->json([
                        'status' => Response::HTTP_BAD_REQUEST,
                        'message' => 'Invalid album ID',
                    ], Response::HTTP_BAD_REQUEST);
                }
            }

            $artistExists = DB::table('tai_khoan')->where('ma_tk', $request->ma_tk_artist)->exists();
            if (!$artistExists) {
                return response()->json([
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => 'Invalid artist account ID',
                ], Response::HTTP_BAD_REQUEST);
            }

            // Lưu bài hát vào cơ sở dữ liệu
            $song = SongModel::create([
                'ma_bai_hat' => $ma_bai_hat,
                'ten_bai_hat' => $request->ten_bai_hat,
                'thoi_luong' => $request->thoi_luong,
                'trang_thai' => 1, // Mặc định active
                'luot_nghe' => 0, // Mặc định 0 lượt nghe
                'hinh_anh' => $request->hinh_anh,
                'ma_album' => $request->ma_album, // Nếu null thì để trống
                // 'link_bai_hat' => $request->link_bai_hat,
                'ngay_phat_hanh' => $request->ngay_phat_hanh,
                'ma_tk_artist' => $request->ma_tk_artist,
                'ma_the_loai' => $request->ma_the_loai, // Liên kết với thể loại
                'doanh_thu' => 0, // Mặc định 0 doanh thu
            ]);

            return response()->json([
                'data' => $song,
                'message' => 'Song created successfully',
                'status' => Response::HTTP_OK,
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
        // Tìm bài hát dựa trên mã bài hát
        $song = SongModel::find($ma_bai_hat);

        if (!$song) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Song not found',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            // Chuyển trạng thái bài hát thành 0 (soft delete)
            $song->trang_thai = 0;
            $song->save();

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Song deleted (soft delete) successfully',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            // Ghi log lỗi nếu xảy ra vấn đề
            Log::error('Song deletion failed: ' . $e->getMessage());

            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Song deletion failed',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
