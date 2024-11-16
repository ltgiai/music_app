<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\ArtistWithdrawalSlipModel;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class PhieuRutTienArtistController extends Controller
{
    // Lấy danh sách phiếu rút tiền artist
    public function index()
    {
        $phieuRutTien = DB::table('phieu_rut_tien_artist')
            ->join('tai_khoan', 'phieu_rut_tien_artist.ma_tk_artist', '=', 'tai_khoan.ma_tk')
            ->select('phieu_rut_tien_artist.*')
            ->get();

        if ($phieuRutTien->isEmpty()) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'No artist withdrawal records found'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'phieuRutTien' => $phieuRutTien->map(function ($phieu) {
                return [
                    'ma_phieu' => $phieu->ma_phieu,
                    'ma_tk_artist' => $phieu->ma_tk_artist,
                    'ngay_rut_tien' => $phieu->ngay_rut_tien,
                    'tong_tien_rut_ra' => $phieu->tong_tien_rut_ra,
                ];
            }),
            'message' => 'Fetched artist withdrawal records successfully',
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }

    // Tạo phiếu rút tiền artist
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ma_tk_artist' => 'required|exists:tai_khoan,ma_tk',
            'tong_tien_rut_ra' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            // Tạo mã phiếu không trùng lặp
            do {
                $date = now()->format('dmY');
                $uniqueNumber = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
                $ma_phieu = 'PHIEU' . $date . $uniqueNumber;
            } while (ArtistWithdrawalSlipModel::where('ma_phieu', $ma_phieu)->exists());

            ArtistWithdrawalSlipModel::create([
                'ma_phieu' => $ma_phieu,
                'ma_tk_artist' => $request->ma_tk_artist,
                'ngay_rut_tien' => now(),
                'tong_tien_rut_ra' => $request->tong_tien_rut_ra,
            ]);

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Artist withdrawal record created successfully',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Artist withdrawal record creation failed: ' . $e->getMessage());
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Artist withdrawal record creation failed'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Hiển thị chi tiết một phiếu rút tiền artist
    public function show($ma_phieu)
    {
        $phieuRutTien = ArtistWithdrawalSlipModel::where('ma_phieu', $ma_phieu)->first();

        if ($phieuRutTien) {
            return response()->json([
                'status' => Response::HTTP_OK,
                'data' => [
                    'ma_phieu' => $phieuRutTien->ma_phieu,
                    'ma_tk_artist' => $phieuRutTien->ma_tk_artist,
                    'ngay_rut_tien' => $phieuRutTien->ngay_rut_tien,
                    'tong_tien_rut_ra' => $phieuRutTien->tong_tien_rut_ra,
                ],
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Artist withdrawal record not found',
            ], Response::HTTP_NOT_FOUND);
        }
    }

    // Cập nhật phiếu rút tiền artist
    public function update(Request $request, $ma_phieu)
    {
        $phieuRutTien = ArtistWithdrawalSlipModel::find($ma_phieu);
        if (!$phieuRutTien) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Artist withdrawal record not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'tong_tien_rut_ra' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $phieuRutTien->update([
                'tong_tien_rut_ra' => $request->tong_tien_rut_ra,
            ]);

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Artist withdrawal record updated successfully',
            ]);
        } catch (\Exception $e) {
            Log::error('Artist withdrawal record update failed: ' . $e->getMessage());
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Artist withdrawal record update failed'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // "Xóa" phiếu rút tiền artist bằng cách cập nhật trạng thái (giả định)
    public function destroy($ma_phieu)
    {
        $phieuRutTien = ArtistWithdrawalSlipModel::find($ma_phieu);
        if (!$phieuRutTien) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Artist withdrawal record not found',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $phieuRutTien->delete();

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Artist withdrawal record deleted successfully',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Artist withdrawal record deletion failed: ' . $e->getMessage());
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Artist withdrawal record deletion failed'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
