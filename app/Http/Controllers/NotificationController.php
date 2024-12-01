<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Account;

class NotificationController extends Controller
{
    public function index() {
        $notifications = Notification::with('taikhoan')->get();
        return response()->json($notifications);
    }
    public function show($ma_tb) {
        $notification = Notification::with('ma_tb')->find($ma_tb);
        if ($notification) {
            return response()->json($notification);
        } else {
            return response()->json(['message' => 'Notification not found'], 404);
        }
    }
    public function store(Request $request)
{
    $lastNotification = Notification::where('ma_tb', 'like', 'NOTI%')
                          ->orderBy('ma_tb', 'desc')
                          ->first();
    if ($lastNotification) {
        $lastIncrement = (int) substr($lastNotification->ma_tb, -4);
        $newIncrement = str_pad($lastIncrement + 1, 4, '0', STR_PAD_LEFT);
    } else {
        $newIncrement = '0001';
    }
    $maTb = 'NOTI' . $newIncrement;
    $notification = Notification::create([
        'ma_tb' => $maTb,
        'ten_tb' => $request->ten_tb,
        'noi_dung_tb' => $request->noi_dung_tb,
        'ma_tk' => $request->ma_tk,
    ]);
    return response()->json($notification, 201);
}

}
