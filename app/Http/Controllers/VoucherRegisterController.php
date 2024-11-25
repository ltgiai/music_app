<?php

namespace App\Http\Controllers;

use App\Models\VoucherRegisterModel;
use Illuminate\Http\Request;
use Carbon\Carbon;
class VoucherRegisterController extends Controller
{
    public function index()
    {
        $voucher_registers = VoucherRegisterModel::all();
        return response()->json($voucher_registers);
    }

    public function show($ma_tk, $ma_goi)
    {
        $voucher_register = VoucherRegisterModel::where('ma_tk', $ma_tk)
        ->where('ma_goi', $ma_goi)
        ->first();

        if (!$voucher_register) {
            return response()->json(['message' => 'VoucherRegister not found'], 404);
        }

        $relationships = $voucher_register->relationships();
        $data = [
            'voucher_register' => $voucher_register,
            'voucher' => $relationships['voucher'],
            'account' => $relationships['account']
        ];
        return response()->json($data);
    }

    public function store(Request $request)
    {
        // Xác thực dữ liệu từ form request
        $request->validate([
            'ma_tk' => 'required|string|max:50',
            'ma_goi' => 'required|string|max:50',
            'thang' => 'required|integer',  // Tháng cần cộng thêm
            'tong_tien_thanh_toan' => 'required|numeric',
            'trang_thai' => 'required|integer',
        ]);

        // Lấy thời gian hiện tại cho ngay_dang_ky
        $ngay_dang_ky = Carbon::now();

        // Tính ngày hết hạn (ngay_het_han = ngay_dang_ky + số tháng từ request)
        $ngay_het_han = $ngay_dang_ky->copy()->addMonths($request->thang);

        // Tạo bản ghi mới trong bảng dang_ky_premium
        $voucher = VoucherRegisterModel::create([
            'ma_tk' => $request->ma_tk,
            'ma_goi' => $request->ma_goi,
            'ngay_dang_ky' => $ngay_dang_ky,
            'ngay_het_han' => $ngay_het_han,
            'tong_tien_thanh_toan' => $request->tong_tien_thanh_toan,
            'trang_thai' => 1,  // Trạng thái luôn là 1
        ]);

        // Trả về kết quả sau khi tạo thành công
        return response()->json([
            'message' => 'Bạn đã đăng ký gói premium này thành công',
            'voucher' => $voucher,
        ], 201);
    }

    public function update(Request $request, $ma_tk, $ma_goi)
    {
        $voucher_register = VoucherRegisterModel::where('ma_tk', $ma_tk)
        ->where('ma_goi', $ma_goi)
        ->first();

        if (!$voucher_register) {
            return response()->json(['message' => 'VoucherRegister not found'], 404);
        }

        $validated = $request->validate([
            'ma_tk' => 'required|exists:accounts,ma_tk',
            'ma_goi' => 'required|exists:vouchers,ma_goi',
            'ngay_dang_ky' => 'required|date',
            'ngay_het_han' => 'required|date',
            'tong_tien_thanh_toan' => 'required|numeric|min:0',
            'trang_thai' => 'required|numeric|between:0,9'
        ]);

        $voucher_register->update($validated);
        return response()->json($voucher_register);
    }

    public function destroy($ma_tk, $ma_goi)
    {
        $voucher_register = VoucherRegisterModel::where('ma_tk', $ma_tk)
        ->where('ma_goi', $ma_goi)
        ->first();

        if (!$voucher_register) {
            return response()->json(['message' => 'VoucherRegister not found'], 404);
        }

        $voucher_register->delete();
        return response()->json(['message' => 'VoucherRegister deleted']);
    }
}