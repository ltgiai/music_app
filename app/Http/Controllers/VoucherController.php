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

    public function show($ma_goi)
    {
        $voucher = VoucherModel::where('ma_goi', $ma_goi)
            ->first();

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
            'trang_thai' => 'required|numeric'
        ]);

        $voucher = VoucherModel::create($validated);
        return response()->json($voucher, 201);
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
        $voucher = VoucherModel::where('ma_goi', $ma_goi)
            ->first();

        if (!$voucher) {
            return response()->json(['message' => 'Voucher not found'], 404);
        }

        $voucher->delete();
        return response()->json(['message' => 'Voucher deleted']);
    }
}
