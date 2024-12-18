<?php

namespace App\Http\Controllers;

use App\Models\SongModel;
use App\Models\SongQualityModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class SongQualityController extends Controller
{
    // Admin muốn xem danh sách tất cả các bài hát
    public function index()
    {
        $link_of_song = DB::table('chat_luong_bai_hat')
            ->join('bai_hat', 'chat_luong_bai_hat.ma_bai_hat', '=', 'bai_hat.ma_bai_hat')
            ->select('chat_luong_bai_hat.*')
            ->get();

        if ($link_of_song->isEmpty()) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'KHÔNG TỒN TẠI LINK BÀI HÁT'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'data' => $link_of_song->map(function ($song) {
                return [
                    'ma_bai_hat' => $song->ma_bai_hat,
                    'chat_luong' => $song->chat_luong,
                    'link_bai_hat' => $song->link_bai_hat,
                ];
            }),
            'message' => 'Get all link of songs successfully',
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }

    // Hiển thị thông tin chi tiết của một bài hát
    // public function show($ma_bai_hat)
    // {
    //     // Lấy thông tin bài hát cùng với quan hệ artist, album, phi_luot_nghe
    //     $link_of_song = SongQualityModel::with('bai_hat')
    //         ->where('ma_bai_hat', $ma_bai_hat)
    //         ->first();

    //     if ($link_of_song) {
    //         return response()->json([
    //             'data' => [
    //                 'ma_bai_hat' => $song->ma_bai_hat,
    //                 'ten_bai_hat' => $song->ten_bai_hat,
                  
    //             'message' => 'Get song details successfully',
    //             'status' => Response::HTTP_OK,
    //         ], Response::HTTP_OK]);
    //     } else {
    //         return response()->json([
    //             'status' => Response::HTTP_NOT_FOUND,
    //             'message' => 'Song not found',
    //         ], Response::HTTP_NOT_FOUND);
    //     }
    // }


    // Xem danh sách tất cả các bài hát theo nghệ sĩ bất kì


    // Lưu trữ một bài hát mới
    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'ten_bai_hat' => 'required',
    //         'thoi_luong' => 'required|numeric',
    //         'hinh_anh' => 'required',
    //         'ma_album' => 'required',
    //         'link_bai_hat' => 'required',
    //         'ngay_phat_hanh' => 'required|date',
    //         'ma_artist' => 'required',
    //         'ma_phi_luot_nghe' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => Response::HTTP_BAD_REQUEST,
    //             'errors' => $validator->errors(),
    //             'message' => 'Validation error'
    //         ], Response::HTTP_BAD_REQUEST);
    //     }

    //     try {
    //         // Tạo mã bài hát duy nhất
    //         do {
    //             $date = now()->format('dmY');
    //             $uniqueNumber = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
    //             $ma_bai_hat = 'SONG-' . $date . $uniqueNumber;
    //         } while (SongModel::where('ma_bai_hat', $ma_bai_hat)->exists());

    //         SongModel::create([
    //             'ma_bai_hat' => $ma_bai_hat,
    //             'ten_bai_hat' => $request->ten_bai_hat,
    //             'thoi_luong' => $request->thoi_luong,
    //             'trang_thai' => 1, // mặc định là active
    //             'luot_nghe' => 0,
    //             'hinh_anh' => $request->hinh_anh,
    //             'ma_album' => $request->ma_album,
    //             'link_bai_hat' => $request->link_bai_hat,
    //             'ngay_phat_hanh' => $request->ngay_phat_hanh,
    //             'ma_artist' => $request->ma_artist,
    //             'ma_phi_luot_nghe' => $request->ma_phi_luot_nghe,
    //             'doanh_thu' => 0,
    //         ]);

    //         return response()->json([
    //             'status' => Response::HTTP_OK,
    //             'message' => 'Song created successfully',
    //         ], Response::HTTP_OK);
    //     } catch (\Exception $e) {
    //         Log::error('Song creation failed: ' . $e->getMessage());
    //         return response()->json([
    //             'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
    //             'message' => 'Song creation failed',
    //         ], Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }

    // // Cập nhật thông tin bài hát
    // public function update(Request $request, $ma_bai_hat)
    // {
    //     $song = SongModel::find($ma_bai_hat);
    //     if (!$song) {
    //         return response()->json([
    //             'status' => Response::HTTP_NOT_FOUND,
    //             'message' => 'Song not found',
    //         ], Response::HTTP_NOT_FOUND);
    //     }

    //     $validator = Validator::make($request->all(), [
    //         'ten_bai_hat' => 'required',
    //         'thoi_luong' => 'required|numeric',
    //         'hinh_anh' => 'required',
    //         'ma_album' => 'required',
    //         'link_bai_hat' => 'required',
    //         'ngay_phat_hanh' => 'required|date',
    //         'trang_thai' => 'required|integer|in:0,1',
    //         'luot_nghe' => 'required|integer',
    //         'doanh_thu' => 'required|integer',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => Response::HTTP_BAD_REQUEST,
    //             'errors' => $validator->errors(),
    //         ], Response::HTTP_BAD_REQUEST);
    //     }

    //     try {
    //         $song->update([
    //             'ten_bai_hat' => $request->ten_bai_hat,
    //             'thoi_luong' => $request->thoi_luong,
    //             'hinh_anh' => $request->hinh_anh,
    //             'ma_album' => $request->ma_album,
    //             'link_bai_hat' => $request->link_bai_hat,
    //             'ngay_phat_hanh' => $request->ngay_phat_hanh,
    //             'trang_thai' => $request->trang_thai,
    //             'luot_nghe' => $request->luot_nghe,
    //             'doanh_thu' => $request->doanh_thu,
    //         ]);

    //         return response()->json([
    //             'status' => Response::HTTP_OK,
    //             'message' => 'Song updated successfully',
    //         ], Response::HTTP_OK);
    //     } catch (\Exception $e) {
    //         Log::error('Song update failed: ' . $e->getMessage());
    //         return response()->json([
    //             'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
    //             'message' => 'Song update failed',
    //         ], Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }

    // // Cập nhật trạng thái bài hát về đã xóa
    // public function destroy($ma_bai_hat)
    // {
    //     $song = SongModel::find($ma_bai_hat);
    //     if (!$song) {
    //         return response()->json([
    //             'status' => Response::HTTP_NOT_FOUND,
    //             'message' => 'Song not found',
    //         ], Response::HTTP_NOT_FOUND);
    //     }

    //     try {
    //         $song->trang_thai = 0; // cập nhật trạng thái là đã xóa
    //         $song->save();

    //         return response()->json([
    //             'status' => Response::HTTP_OK,
    //             'message' => 'Song deleted successfully',
    //         ], Response::HTTP_OK);
    //     } catch (\Exception $e) {
    //         Log::error('Song deletion failed: ' . $e->getMessage());
    //         return response()->json([
    //             'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
    //             'message' => 'Song deletion failed',
    //         ]);
    //     }
    // }
}
