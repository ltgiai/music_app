<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index() {
        return response()->json(User::all());
    }
    public function show($ma_tk) {
        $user = User::with('tai_khoan')->find($ma_tk);
        if ($user) {
            return response()->json($user);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }

    public function update(Request $request, $ma_tk){
        $user = User::find($ma_tk);
        if ($user) {
            $user->update([
            'ten_user' => $request->ten_user,
            'anh_dai_dien' => $request->anh_dai_dien,
            ]);
            return response()->json($user);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }

}