<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index() {
        $notifications = Notification::with('taikhoan')->get();
        return response()->json($notifications);
    }
    public function show($ma_tb) {
        $notification = Notification::with('ma_tb')->find($ma_tk);
        if ($notification) {
            return response()->json($notification);
        } else {
            return response()->json(['message' => 'Notification not found'], 404);
        }
    }

    public function store(Request $request){
        $account = Account::create([
            'ma_tb' => $request->ma_tb,  // sau này thì auto tạo tự động mã tb
            'ten_tb' => $request->ten_tb,
            'noi_dung_tb' => $request->noi_dung_tb,
            'ma_tk' => $request->ma_tk,
        ]);
        return response()->json($account, 201);
    }
}
