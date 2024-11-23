<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Decentralization;
use App\Models\FunctionalDetail;
class DecentralizationController extends Controller
{
    public function index(){
        $decentralizations = Decentralization::all();
        return response()->json($decentralizations);
    }
    public function show($ma_phan_quyen){
        $decentralization = Decentralization::with(['chuc_nang'])->find($ma_phan_quyen);

        if ($decentralization) {
            return response()->json($decentralization);
        } else {
            return response()->json(['message' => 'Decentralization not found'], 404);
        }
    }

    public function updateTenQuyenHan(Request $request, $ma_phan_quyen){
        $ten_quyen_han_moi = $request->input('ten_quyen_han');
        // Tìm mã quyền hạn bằng Eloquent
        $decentralization = Decentralization::find($ma_phan_quyen);
        if ($decentralization) {
            $decentralization->ten_quyen_han = $ten_quyen_han_moi;
            $decentralization->save();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thành công tên quyền hạn.',
                'data' => $decentralization
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy mã quyền hạn.'
            ]);
        }
    }

//     public function store(Request $request)
// {
//     // Retrieve the last created `ma_chuc_nang` with the prefix 'FUNC'
//     $lastDecentralization = Decentralization::where('ma_phan_quyen', 'like', 'AUTH%')
//                               ->orderBy('ma_phan_quyen', 'desc')
//                               ->first();

//     // Extract the increment part or start at 1 if no previous records exist
//     if ($lastDecentralization) {
//         // Get the last 4 digits, convert to integer, and increment by 1
//         $lastIncrement = (int) substr($lastDecentralization->ma_phan_quyen, -4);
//         $newIncrement = str_pad($lastIncrement + 1, 4, '0', STR_PAD_LEFT);
//     } else {
//         // Start from '0001' if there is no matching record
//         $newIncrement = '0001';
//     }

//     // Generate the full `ma_chuc_nang` with the new increment
//     $maChucNang = 'AUTH' . $newIncrement;

//     // Create the function with the generated `ma_chuc_nang`
//     $decentralization = Decentralization::create([
//         'ma_phan_quyen' => $maChucNang,
//         'ten_quyen_han' => $request->ten_quyen_han,
//         'ngay_tao' => $request->ngay_tao,
//         'tinh_trang' => 1,
//     ]);

//     return response()->json($decentralization, 201);
// }
public function store(Request $request)
{
    $tenQuyenHan = $request->ten_quyen_han;
    $ngayTao = $request->ngay_tao;
    $chiTietPhanQuyen = $request->chi_tiet_phan_quyen;

    // Tạo mã phân quyền mới
    $lastDecentralization = Decentralization::where('ma_phan_quyen', 'like', 'AUTH%')
                                            ->orderBy('ma_phan_quyen', 'desc')
                                            ->first();

    if ($lastDecentralization) {
        $lastIncrement = (int) substr($lastDecentralization->ma_phan_quyen, -4);
        $newIncrement = str_pad($lastIncrement + 1, 4, '0', STR_PAD_LEFT);
    } else {
        $newIncrement = '0001';
    }

    $maPhanQuyen = 'AUTH' . $newIncrement;

    // Tạo phân quyền mới
    $decentralization = Decentralization::create([
        'ma_phan_quyen' => $maPhanQuyen,
        'ten_quyen_han' => $tenQuyenHan,
        'ngay_tao' => $ngayTao,
        'tinh_trang' => 1,
    ]);

    // Duyệt qua mảng chi tiết quyền hạn và thêm vào bảng FunctionalDetail
    foreach ($chiTietPhanQuyen as $item) {
        FunctionalDetail::create([
            'ma_phan_quyen' => $maPhanQuyen,
            'ma_chuc_nang' => $item['ma_chuc_nang'],
            'xem' => $item['xem'],
            'them' => $item['them'],
            'sua' => $item['sua'],
            'xoa' => $item['xoa'],
        ]);
    }

    // Trả về kết quả
    return response()->json([
        'message' => 'Tạo mới phân quyền và chức năng thành công!',
        'decentralization' => $decentralization,
    ], 201);
}

    public function update(Request $request, $ma_phan_quyen){
        $decentralization = Decentralization::find($ma_phan_quyen);

        if ($decentralization) {
            $decentralization->update([
            'ten_quyen_han' => $request->ten_quyen_han,
            'tinh_trang' => $request->tinh_trang,
            ]);
            return response()->json($decentralization);
        } else {
            return response()->json(['message' => 'Decentralization not found'], 404);
        }
    }
    public function destroy($ma_phan_quyen)
{
    // Tìm phân quyền theo mã
    $phanQuyen = Decentralization::where('ma_phan_quyen', $ma_phan_quyen)->first();

    if (!$phanQuyen) {
        return response()->json(['message' => 'Phân quyền không tồn tại!'], 404);
    }

    // Xóa các chi tiết phân quyền liên quan
    $phanQuyen->chuc_nang()->detach(); // Xóa dữ liệu từ bảng pivot (chi_tiet_phan_quyen)

    // Xóa phân quyền
    $phanQuyen->delete();

    return response()->json(['message' => 'Xóa phân quyền và chi tiết liên quan thành công!'], 200);
}

    public function attachFunctionn($ma_phan_quyen, $ma_chuc_nang)
    {
        $decentralization = Decentralization::find($ma_phan_quyen);
        if ($decentralization) {
            $decentralization->chucNangs()->attach($ma_chuc_nang);
            return response()->json(['message' => 'Functionn attached to quyen']);
        } else {
            return response()->json(['message' => 'Decentralization not found'], 404);
        }
    }
}
