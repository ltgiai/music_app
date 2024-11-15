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
        $notification = Notification::with('ma_tb')->find($ma_tk);
        if ($notification) {
            return response()->json($notification);
        } else {
            return response()->json(['message' => 'Notification not found'], 404);
        }
    }

    // public function store(Request $request){
    //     $notification = Notification::create([
    //         'ma_tb' => $request->ma_tb,  // sau này thì auto tạo tự động mã tb
    //         'ten_tb' => $request->ten_tb,
    //         'noi_dung_tb' => $request->noi_dung_tb,
    //         'ma_tk' => $request->ma_tk,
    //     ]);
    //     return response()->json($account, 201);
    // }
    public function store(Request $request)
{
    // Retrieve the last created `ma_tb` with the prefix 'NOTI'
    $lastNotification = Notification::where('ma_tb', 'like', 'NOTI%')
                          ->orderBy('ma_tb', 'desc')
                          ->first();

    // Extract the increment part or start at 1 if no previous records exist
    if ($lastNotification) {
        // Get the last 4 digits, convert to integer, and increment by 1
        $lastIncrement = (int) substr($lastNotification->ma_tb, -4);
        $newIncrement = str_pad($lastIncrement + 1, 4, '0', STR_PAD_LEFT);
    } else {
        // Start from '0001' if there is no matching record
        $newIncrement = '0001';
    }

    // Generate the full `ma_tb` with the new increment
    $maTb = 'NOTI' . $newIncrement;

    // Create the account with the generated `ma_tb`
    $notification = Notification::create([
        'ma_tb' => $maTb,
        'ten_tb' => $request->ten_tb,
        'noi_dung_tb' => $request->noi_dung_tb,
        'ma_tk' => $request->ma_tk,
    ]);

    return response()->json($notification, 201);
}

}
