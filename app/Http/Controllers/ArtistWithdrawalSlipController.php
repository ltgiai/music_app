<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\ArtistWithdrawalSlipModel;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class ArtistWithdrawalSlipController extends Controller
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
                    'ma_bank' => $phieu->bank_id,
                    'ten_bank' => $phieu->bank_name
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
            'ngan_hang' => 'required|string|max:255',
            'tk_ngan_hang' => 'required|string|max:255'
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
            // Lấy mã phiếu lớn nhất hiện tại
            $lastRecord = ArtistWithdrawalSlipModel::latest('ma_phieu')->first();

            // Xử lý số thứ tự từ mã phiếu lớn nhất
            $lastNumber = 0; // Mặc định nếu không có bản ghi nào
            if ($lastRecord) {
                $lastNumber = (int) substr($lastRecord->ma_phieu, 3); // Cắt bỏ 'PRT'
            }

            $newNumber = $lastNumber + 1;
            $ma_phieu = 'PRT' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

            // Tạo bản ghi mới
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
    public function show($ma_tai_khoan)
    {
        // Truy vấn để lấy chi tiết phiếu rút tiền của nghệ sĩ
        $phieuRutTien = DB::table('phieu_rut_tien_artist')
            ->where('ma_tk_artist', $ma_tai_khoan)
            ->select('ma_phieu', 'ma_tk_artist', 'ngay_rut_tien', 'tong_tien_rut_ra')
            ->first(); // Lấy một bản ghi duy nhất

        // Kiểm tra nếu không tìm thấy
        if (!$phieuRutTien) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Artist withdrawal record not found',
            ], Response::HTTP_NOT_FOUND);
        }

        // Trả về dữ liệu nếu tìm thấy
        return response()->json([
            'status' => Response::HTTP_OK,
            'data' => $phieuRutTien,
        ], Response::HTTP_OK);
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
