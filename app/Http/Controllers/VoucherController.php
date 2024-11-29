<?php

namespace App\Http\Controllers;

use App\Models\VoucherModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class VoucherController extends Controller
{   
    public function index() {
        $voucher = VoucherModel::all();
        return response()->json($voucher);
    }

    
    public function renderListOfVouchers()
    {
        $voucher = DB::table('goi_premium')
            ->select('goi_premium.*')
            ->get();

        if ($voucher->isEmpty()) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'ERROR 404 - Not founded'
            ], Response::HTTP_NOT_FOUND);
        }

        $formattedVouchers = $voucher->map(function ($item) {
            return [
                'ma_goi' => $item->ma_goi,
                'ten_goi' => $item->ten_goi,
                'thoi_han' => $item->thoi_han,
                'gia_goi' => $item->gia_goi,
                'doanh_thu' => $item->doanh_thu,
                'mo_ta' => $item->mo_ta,
                'trang_thai' => $item->trang_thai
            ];
        });

        return response()->json([
            'data' => $formattedVouchers,
            'message' => 'Get all vouchers successfully',
            'status' => Response::HTTP_OK
        ], Response::HTTP_OK);
    }

    public function renderVoucherRegister()
    {
        // Lấy dữ liệu từ các bảng liên quan
        $registers = DB::table('goi_premium')
            ->join('dang_ky_premium', 'goi_premium.ma_goi', '=', 'dang_ky_premium.ma_goi')
            ->join('tai_khoan', 'tai_khoan.ma_tk', '=', 'dang_ky_premium.ma_tk')
            ->select(
                'goi_premium.ma_goi',
                'goi_premium.ten_goi',
                'goi_premium.thoi_han',
                'goi_premium.gia_goi',
                'goi_premium.mo_ta',
                'dang_ky_premium.ngay_dang_ky',
                'dang_ky_premium.ngay_het_han',
                'dang_ky_premium.tong_tien_thanh_toan',
                'dang_ky_premium.trang_thai as trang_thai_dang_ky',
                'tai_khoan.ma_tk',
                'tai_khoan.email',
                'tai_khoan.trang_thai as trang_thai_tai_khoan'
            )
            ->get();

        // Kiểm tra nếu không có dữ liệu
        if ($registers->isEmpty()) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'ERROR 404 - No vouchers found',
            ], Response::HTTP_NOT_FOUND);
        }

        // Định dạng lại dữ liệu để trả về
        $formattedRegisters = $registers->map(function ($item) {
            return [
                'tai_khoan' => [
                    'ma_tk' => $item->ma_tk,
                    'email' => $item->email,
                    'trang_thai' => $item->trang_thai_tai_khoan,
                ],
                'dang_ky_premium' => [
                    'ngay_dang_ky' => $item->ngay_dang_ky,
                    'ngay_ket_thuc' => $item->ngay_het_han,
                    'trang_thai' => $item->trang_thai_dang_ky,
                ],
                'goi_premium' => [
                    'ma_goi' => $item->ma_goi,
                    'ten_goi' => $item->ten_goi,
                    'thoi_han' => $item->thoi_han,
                    'gia_goi' => $item->gia_goi,
                    'mo_ta' => $item->mo_ta,
                ],
            ];
        });

        // Trả về JSON
        return response()->json([
            'data' => $formattedRegisters,
            'message' => 'Get all registers successfully',
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }


    public function show($ma_goi) {
        $premium = VoucherModel::find($ma_goi);
        if ($premium) {
            return response()->json($premium);
        } else {
            return response()->json(['message' => 'Premium not found'], 404);
        }
    }

    public function store(Request $request)
    {
        // Lấy giá trị ma_goi cuối cùng trong cơ sở dữ liệu
        $lastPackage = VoucherModel::orderBy('ma_goi', 'desc')->first();
        
        // Lấy số cuối cùng trong ma_goi (xxxx), giả sử định dạng là GOIxxxx
        $lastNumber = $lastPackage ? (int)substr($lastPackage->ma_goi, 3) : 0;
        
        // Tạo giá trị mới cho ma_goi
        $newMaGoi = 'GOI' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        // Tạo gói premium với ma_goi mới
        $premiumPackage = VoucherModel::create([
            'ma_goi' => $newMaGoi,
            'ten_goi' => $request->ten_goi,
            'thoi_han' => $request->thoi_han,
            'gia_goi' => $request->gia_goi,
            'doanh_thu' => $request->doanh_thu ?? '0.000',
            'mo_ta' => "Nghe nhạc chất lượng cao,Trải nghiệm nghe nhạc không có quảng cáo",
            'trang_thai' => 1,
        ]);

        return response()->json(['message' => 'Gói premium đã được thêm thành công', 'data' => $premiumPackage], 201);
    }

    public function updateStatus(Request $request, $ma_goi)
    {
        // Xác thực dữ liệu đầu vào (nếu cần)
        $request->validate([
            'trang_thai' => 'required|boolean', // Chỉ nhận giá trị 0 hoặc 1
        ]);

        // Tìm gói premium theo `ma_goi`
        $voucher = VoucherModel::find($ma_goi);

        if (!$voucher) {
            return response()->json([
                'message' => 'Không tìm thấy gói premium với mã gói này!',
            ], 404);
        }

        // Cập nhật trạng thái
        $voucher->trang_thai = $request->trang_thai;
        $voucher->save();

        return response()->json([
            'message' => 'Cập nhật trạng thái thành công!',
            'data' => $voucher,
        ], 200);
    }

    public function update(Request $request, $ma_goi)
    {
        $voucher = VoucherModel::where('ma_goi', $ma_goi)
            ->first();

        if (!$voucher) {
            return response()->json(['message' => 'Voucher not found'], 404);
        }

        $validated = $request->validate([
            'ma_goi' => 'required|exists:vouchers,ma_goi',
            'ten_goi' => 'required|string|max:50',
            'thoi_han' => 'required|numeric|min:0',
            'gia_goi' => 'required|numeric|min:0',
            'doanh_thu' => 'required|numeric|min:0',
            'mo_ta' => 'required|string',
            'trang_thai' => 'required|numeric|between:0,9'
        ]);

        $voucher->update($validated);
        return response()->json($voucher);
    }

    public function destroy($ma_goi)
    {
        // Tìm gói theo id
        $voucher = VoucherModel::find($ma_goi);

        // Nếu không tìm thấy gói, trả về lỗi 404
        if (!$voucher) {
            return response()->json([
                'message' => 'Gói không tồn tại.'
            ], Response::HTTP_NOT_FOUND);
        }

        // Xóa gói
        $voucher->delete();

        // Trả về phản hồi thành công
        return response()->json([
            'message' => 'Gói đã được xóa thành công.'
        ], Response::HTTP_OK);
    }
}
