<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account;
class AccountController extends Controller
{
    public function index() {
        $accounts = Account::with('phan_quyen')->get();
        // $accounts = Account::all();
        // dd($accounts);
        return response()->json($accounts);
    }

    public function show($ma_tk) {
        $account = Account::with('phan_quyen')->find($ma_tk);
        if ($account) {
            return response()->json($account);
        } else {
            return response()->json(['message' => 'Account not found'], 404);
        }
    }

    public function store(Request $request){
        $account = Account::create([
            'ma_tk' => $request->ma_tk,  // sau này thì auto tạo tự động mã tk
            'gmail' => $request->gmail,
            'mat_khau' => $request->mat_khau,
            'ngay_tao' => $request->ngay_tao,
            'trang_thai' => $request->trang_thai, // mật định là 1
            'ma_phan_quyen' => $request->ma_phan_quyen,
            'ma_tk' => $request->ma_tk,
        ]);

        $account->user()->create([
            'ten_user' => $request->ten_user,
            'anh_dai_dien' => null,
        ]);

        return response()->json($account, 201);
    }

    public function update(Request $request, $ma_tk){
        $account = Account::find($ma_tk);

        if ($account) {
            $account->update([
            'gmail' => $request->gmail,
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
