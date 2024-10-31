<?php

namespace App\Http\Controllers;

use App\Models\VoucherModel;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = VoucherModel::all();
        return response()->json($vouchers);
    }

    public function show($id)
    {
        $voucher = VoucherModel::find($id);

        if (!$voucher) {
            return response()->json(['message' => 'Voucher not found'], 404);
        }

        $relationships = $voucher->relationships();
        $data = [
            'voucher' => $voucher,
        ];
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ma_goi' => 'required|exists:vouchers,ma_goi',
            'ten_goi' => 'required|string|max:50',
            'thoi_han' => 'required|numeric|min:0',
            'gia_goi' => 'required|numeric|min:0',
            'doanh_thu' => 'required|numeric|min:0',
            'mo_ta' => 'required|string',
            'trang_thai' => 'required|boolean'
        ]);

        $voucher = VoucherModel::create($validated);
        return response()->json($voucher, 201);
    }

    public function update(Request $request, $id)
    {
        $voucher = VoucherModel::find($id);

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
            'trang_thai' => 'required|boolean'
        ]);

        $voucher->update($validated);
        return response()->json($voucher);
    }

    public function destroy($id)
    {
        $voucher = VoucherModel::find($id);

        if (!$voucher) {
            return response()->json(['message' => 'Voucher not found'], 404);
        }

        $voucher->delete();
        return response()->json(['message' => 'Voucher deleted']);
    }
}