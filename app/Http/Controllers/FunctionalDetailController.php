<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FunctionalDetail;
class FunctionalDetailController extends Controller
{
    public function update(Request $request, $ma_phan_quyen, $ma_chuc_nang)
    {
        // Tìm phân quyền theo ma_phan_quyen và ma_chuc_nang
        $chiTietPhanQuyen = FunctionalDetail::where('ma_phan_quyen', $ma_phan_quyen)
                                              ->where('ma_chuc_nang', $ma_chuc_nang)
                                              ->first();

        // Kiểm tra nếu không tìm thấy phân quyền
        if (!$chiTietPhanQuyen) {
            return response()->json(['message' => 'Không tìm thấy phân quyền với mã phân quyền và chức năng này'], 404);
        }

        // Cập nhật các trường cần thiết
        $chiTietPhanQuyen->update([
            'xem' => $request->input('xem'),
            'them' => $request->input('them'),
            'sua' => $request->input('sua'),
            'xoa' => $request->input('xoa'),
        ]);

        return response()->json(['message' => 'Cập nhật phân quyền thành công']);
    }
    public function delete($ma_phan_quyen, $ma_chuc_nang)
    {
        // Tìm bản ghi theo ma_phan_quyen và ma_chuc_nang
        $chiTietPhanQuyen = FunctionalDetail::where('ma_phan_quyen', $ma_phan_quyen)
                                              ->where('ma_chuc_nang', $ma_chuc_nang)
                                              ->first();

        // Kiểm tra nếu không tìm thấy bản ghi
        if (!$chiTietPhanQuyen) {
            return response()->json(['message' => 'Không tìm thấy phân quyền với mã phân quyền và chức năng này'], 404);
        }

        // Xóa bản ghi
        $chiTietPhanQuyen->delete();
        return response()->json(['message' => 'Xóa phân quyền thành công']);
    }
}
