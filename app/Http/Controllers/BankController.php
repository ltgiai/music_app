<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bank;
use Illuminate\Support\Facades\DB;
class BankController extends Controller
{
    public function index()
    {
        $banks = Bank::all();
        return response()->json($banks);
    }

    // Lấy thông tin một ngân hàng cụ thể
    public function show($bank_id)
    {
        $bank = Bank::find($bank_id);
        
        if (!$bank) {
            return response()->json(['message' => 'Bank not found'], 404);
        }
        
        return response()->json($bank);
    }

    public function withdraw(Request $request, $bank_id)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'so_tien_tru' => 'required|numeric|min:0',
        ]);

        // Lấy thông tin ngân hàng từ cơ sở dữ liệu
        $bank = Bank::where('bank_id', $bank_id)->where('bank_name', $request->bank_name)->first();

        // Kiểm tra xem ngân hàng có tồn tại không
        if (!$bank) {
            return response()->json(['message' => 'bank_id or bank_name không tồn tại'], 404);
        }

        // Kiểm tra xem số tiền trừ có lớn hơn số dư khả dụng không
        if ($request->so_tien_tru > $bank->so_du_kha_dung) {
            return response()->json(['message' => 'Số tiền trừ lớn hơn số dư khả dụng'], 400);
        }

        // Cập nhật số dư khả dụng
        DB::beginTransaction();  // Bắt đầu transaction

        try {
            $bank->so_du_kha_dung -= $request->so_tien_tru;
            $bank->save();  // Lưu thay đổi vào cơ sở dữ liệu

            DB::commit();  // Commit transaction
            return response()->json(['message' => 'Rút tiền thành công', 'new_balance' => $bank->so_du_kha_dung], 200);
        } catch (\Exception $e) {
            DB::rollBack();  // Rollback nếu có lỗi
            return response()->json(['message' => 'Lỗi khi cập nhật số dư', 'error' => $e->getMessage()], 500);
        }
    }
}
