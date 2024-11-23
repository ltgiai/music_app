<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FunctionalDetail;
class FunctionalDetailController extends Controller
{
    public function update(Request $request){
        $data = $request->only(['ma_phan_quyen', 'ma_chuc_nang', 'xem', 'them', 'sua', 'xoa']);

        if (!isset($data['ma_phan_quyen'], $data['ma_chuc_nang'])) {
            return response()->json(['message' => 'Thiếu thông tin cần thiết!'], 400);
        }

        $updated = FunctionalDetail::where('ma_phan_quyen', $data['ma_phan_quyen'])
            ->where('ma_chuc_nang', $data['ma_chuc_nang'])
            ->update([
                'xem' => $data['xem'] ?? 0,
                'them' => $data['them'] ?? 0,
                'sua' => $data['sua'] ?? 0,
                'xoa' => $data['xoa'] ?? 0,
            ]);

        if ($updated) {
            return response()->json(['message' => 'Cập nhật thành công!']);
        } else {
            return response()->json(['message' => 'Không tìm thấy bản ghi để cập nhật!'], 404);
        }
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

    public function getFunctionalDetail(Request $request)
    {
        $maPhanQuyen = $request->query('ma_phan_quyen');
        $maChucNang = $request->query('ma_chuc_nang');

        // Kiểm tra các tham số đầu vào
        if (!$maPhanQuyen || !$maChucNang) {
            return response()->json(['message' => 'Vui lòng cung cấp mã phân quyền và mã chức năng!'], 400);
        }

        // Lấy chi tiết từ bảng chi_tiet_phan_quyen
        $functionalDetail = FunctionalDetail::where('ma_phan_quyen', $maPhanQuyen)
            ->where('ma_chuc_nang', $maChucNang)
            ->first();

        // Nếu không tìm thấy, trả về lỗi
        if (!$functionalDetail) {
            return response()->json(['message' => 'Không tìm thấy dữ liệu!'], 404);
        }

        // Trả về dữ liệu
        return response()->json([
            'xem'           => $functionalDetail->xem,
            'them'          => $functionalDetail->them,
            'sua'           => $functionalDetail->sua,
            'xoa'           => $functionalDetail->xoa,
        ], 200);
    }
}
