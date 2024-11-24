<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PhiLuotNghe;
class PhiLuotNgheController extends Controller
{   
    public function index() {
        $phiLuotNghe = PhiLuotNghe::all();
        return response()->json($phiLuotNghe);
    }
    
    public function updateGiaTien(Request $request, $ma_phi)
    {
        // Validate dữ liệu
        $validated = $request->validate([
            'gia_tien_luot_nghe' => 'required|numeric|min:0',
        ]);

        // Tìm bản ghi theo ma_phi
        $phiLuotNghe = PhiLuotNghe::find($ma_phi);

        if (!$phiLuotNghe) {
            return response()->json(['message' => 'Mã phí không tồn tại'], 404);
        }

        // Cập nhật gia_tien_luot_nghe
        $phiLuotNghe->gia_tien_luot_nghe = $validated['gia_tien_luot_nghe'];
        $phiLuotNghe->save();

        return response()->json([
            'message' => 'Cập nhật thành công',
            'data' => $phiLuotNghe
        ], 200);
    }
}
