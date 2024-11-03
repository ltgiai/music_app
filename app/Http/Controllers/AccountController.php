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

    // public function store(Request $request){
    //     $account = Account::create([
    //         'ma_tk' => $request->ma_tk,  // sau này thì auto tạo tự động mã tk
    //         'gmail' => $request->gmail,
    //         'mat_khau' => $request->mat_khau,
    //         'ngay_tao' => $request->ngay_tao,
    //         'trang_thai' => $request->trang_thai, // mật định là 1
    //         'ma_phan_quyen' => $request->ma_phan_quyen,
    //         'ma_tk' => $request->ma_tk,
    //     ]);

    //     $account->user()->create([
    //         'ten_user' => $request->ten_user,
    //         'anh_dai_dien' => null,
    //     ]);

    //     return response()->json($account, 201);
    // }
    public function store(Request $request){
        // Get the date part in ddmmyy format
        $datePart = now()->format('dmy');
    
        // Retrieve the last created `ma_tk` with a matching date prefix, if any
        $lastAccount = Account::where('ma_tk', 'like', 'ACC' . $datePart . '%')
                              ->orderBy('ma_tk', 'desc')
                              ->first();
    
        // Extract the increment part or start at 1 if none exists
        if ($lastAccount) {
            // Get the last 4 digits and increment by 1
            $lastIncrement = (int) substr($lastAccount->ma_tk, -4);
            $newIncrement = str_pad($lastIncrement + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // Start from 0001 if no matching date prefix exists
            $newIncrement = '0001';
        }
    
        // Generate the full `ma_tk` without dashes
        $maTk = 'ACC' . $datePart . $newIncrement;
    
        // Create the account
        $account = Account::create([
            'ma_tk' => $maTk,
            'gmail' => $request->gmail,
            'mat_khau' => $request->mat_khau,
            'ngay_tao' => $request->ngay_tao,
            'trang_thai' => $request->trang_thai ?? 1, // default to 1 if not provided
            'ma_phan_quyen' => $request->ma_phan_quyen,
        ]);
    
        // Create the associated user
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
