<?php

namespace App\Http\Controllers;

use App\Models\VoucherRegisterModel;
use Illuminate\Http\Request;

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
        $validated = $request->validate([
            'ma_tk' => 'required|exists:accounts,ma_tk',
            'ma_goi' => 'required|exists:vouchers,ma_goi',
            'ngay_dang_ky' => 'required|date',
            'ngay_het_han' => 'required|date',
            'gia_goi' => 'required|numeric|min:0',
            'trang_thai' => 'required|numeric'
        ]);

        $voucher_register = VoucherRegisterModel::create($validated);
        return response()->json($voucher_register, 201);
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
            'gia_goi' => 'required|numeric|min:0',
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