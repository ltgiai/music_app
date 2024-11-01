<?php

namespace App\Http\Controllers;

use App\Models\AlbumModel;
use App\Models\AlbumAccountModel;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AlbumController extends Controller
{
    public function index()
    {
        $albums = AlbumModel::all();

        if ($albums->isEmpty()) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'success' => false,
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

        return response()->json($albums);
    }

    // Lưu trữ dữ liệu
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ten_album' => 'required',
            'ngay_tao' => 'required',
            'hinh_anh' => 'required',
            'trang_thai' => 'required',
            'so_luong_bai_hat' => 'required',
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
                'trang_thai' => 1,
                'so_luong_bai_hat' => $request->so_luong_bai_hat,
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
        echo 'find success';
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
            'trang_thai' => 'required',
            'so_luong_bai_hat' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
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
            $album->delete();
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Album deleted successfully',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Album delete failed: ' . $e->getMessage());
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Album delete failed'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
