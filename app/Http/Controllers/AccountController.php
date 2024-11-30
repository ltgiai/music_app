<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Facades\DB;
class AccountController extends Controller
{
    public function index() {
        $accounts = Account::with('phan_quyen')->get();
        return response()->json($accounts);
    }

    public function getAllAccount() {
        $accounts = Account::with(['phan_quyen','user'])->get();
        return response()->json($accounts);
    }

    public function convertUserToArtist(Request $request, $ma_tk)
    {
        $account = Account::find($ma_tk);
        if (!$account) {
            return response()->json([
                'message' => 'Tài khoản không tồn tại.',
            ], 404);
        }
        if ($account->ma_phan_quyen === 'AUTH0002') {
            return response()->json([
                'message' => 'Tài khoản đã là nghệ sĩ.',
            ], 400);
        }
        $account->ma_phan_quyen = 'AUTH0002';
        $account->save();

        return response()->json([
            'message' => 'Chuyển tài khoản thành nghệ sĩ thành công.',
            'account' => $account,
        ], 200);
    }

    public function updateTrangThai(Request $request, $ma_tk)
    {
        $validated = $request->validate([
            'trang_thai' => 'required|integer|in:0,1,2',
        ]);
        $account = Account::find($ma_tk);

        if (!$account) {
            return response()->json([
                'message' => 'Tài khoản không tồn tại.',
            ], 404);
        }
        $account->trang_thai = $validated['trang_thai'];
        $account->save();

        return response()->json([
            'message' => 'Cập nhật trạng thái thành công.',
            'account' => $account,
        ], 200);
    }

    public function show($ma_tk) {
        $account = Account::with(['phan_quyen','user'])->find($ma_tk);
        if ($account) {
            return response()->json($account);
        } else {
            return response()->json(['message' => 'Account not found'], 404);
        }
    }

    public function showVoucher($ma_tk) {
        $account = Account::with(['voucher'])->find($ma_tk);

        if ($account) {
            return response()->json($account);
        } else {
            return response()->json(['message' => 'Account not found'], 404);
        }
    }

    public function store(Request $request){
        $lastMaTk = DB::table('tai_khoan')->orderBy('ma_tk', 'desc')->first();

        // Tách phần ngày tháng ra khỏi ma_tk
        $datePart = date('dmY');  // ddmmyyyy
        
        // Tạo số tự động (xxxx) dựa trên ma_tk cuối cùng
        $lastNumber = $lastMaTk ? (int)substr($lastMaTk->ma_tk, -4) : 0;
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        // Tạo ma_tk mới
        // return $prefix . $datePart . $newNumber;
        $maTk = 'ACC' . $datePart . $newNumber;
    
        // Create the account
        $account = Account::create([
            'ma_tk' => $maTk,
            'token' => null,
            'email' => $request->email,
            'mat_khau' => $request->password,
            'ngay_tao' => now(),
            'trang_thai' =>  1, // default to 1 if not provided
            'ma_phan_quyen' => 'AUTH0003',
        ]);
    
        // Create the associated user
        $user = User::create([
            'ma_tk' => $maTk,
            'ten_user' => $request->ten_user,
            'anh_dai_dien' => $request->anh_dai_dien,
        ]);
    
        return response()->json($account, 201);
    }


    public function storeAdmin(Request $request){
        $lastMaTk = DB::table('tai_khoan')->orderBy('ma_tk', 'desc')->first();

        // Tách phần ngày tháng ra khỏi ma_tk
        $datePart = date('dmY');  // ddmmyyyy
        
        // Tạo số tự động (xxxx) dựa trên ma_tk cuối cùng
        $lastNumber = $lastMaTk ? (int)substr($lastMaTk->ma_tk, -4) : 0;
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        // Tạo ma_tk mới
        // return $prefix . $datePart . $newNumber;
        $maTk = 'ACC' . $datePart . $newNumber;
    
        // Create the account
        $account = Account::create([
            'ma_tk' => $maTk,
            'token' => null,
            'email' => $request->email,
            'mat_khau' => $request->password,
            'ngay_tao' => now(),
            'trang_thai' =>  1,
            'ma_phan_quyen' => $request->decentralization,
        ]);
    
        // Create the associated user
        $user = User::create([
            'ma_tk' => $maTk,
            'ten_user' => $request->ten_user,
            'anh_dai_dien' => "http://127.0.0.1:8000/storage/images/AvatarDefault.png",
        ]);
    
        return response()->json($account, 201);
    }
    

    public function update(Request $request, $ma_tk){
        $account = Account::find($ma_tk);

        if ($account) {
            $account->update([
            'email' => $request->email,
            'mat_khau' => $request->mat_khau,
            'trang_thai' => $request->trang_thai,
            'ma_phan_quyen' => $request->ma_phan_quyen,
            ]);
            return response()->json($account);
        } else {
            return response()->json(['message' => 'Account not found'], 404);
        }
    }
    public function destroy($id){
        $account = Account::find($id);
        if ($account) {
            $account->delete();
            return response()->json(['message' => 'Account deleted successfully']);
        } else {
            return response()->json(['message' => 'Account not found'], 404);
        }
    }
}
