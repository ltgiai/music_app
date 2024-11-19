<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function forgotPassword(Request $request)
    {   
        $request->validate(['email' => 'required|email']);
        $taikhoan = Account::where('email', $request->email)->first();

        if (!$taikhoan) {
            return response()->json(['message' => 'Email không tồn tại'], 404);
        }

        // Tạo token reset mật khẩu và lưu vào database
        $taikhoan->token = Str::random(60);
        $taikhoan->save();

        // Gửi email với link reset mật khẩu
        Mail::send('emails.reset-password', ['token' => $taikhoan->token], function ($message) use ($taikhoan) {
            $message->to($taikhoan->email);
            $message->subject('Link đặt lại mật khẩu');
        });

        return response()->json(['message' => 'Link đặt lại mật khẩu đã được gửi đến email của bạn']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => [
                'required',
                'min:8', // Mật khẩu tối thiểu 6 ký tự
                'confirmed', // Mật khẩu và xác nhận mật khẩu phải trùng khớp
                function($attribute, $value, $fail) {
                    if (!preg_match('/\d/', $value)) {
                        return $fail('Mật khẩu phải có ít nhất một chữ số.');
                    }
                    if (!preg_match('/[!@#\$%\^\&*\)\(+=._-]/', $value)) {
                        return $fail('Mật khẩu phải có ít nhất một ký tự đặc biệt.');
                    }
                },
            ],
        ]);

        $taikhoan = Account::where('token', $request->token)->first();

        if (!$taikhoan) {
            return response()->json(['message' => 'Token không hợp lệ'], 404);
        }

        // Đặt lại mật khẩu và xóa token
        $taikhoan->mat_khau = $request->password;  // Gán trực tiếp mật khẩu, không mã hóa
        $taikhoan->token = null;
        $taikhoan->save();

        return response()->json(['message' => 'Mật khẩu đã được đặt lại thành công']);
    }

    public function login(Request $request)
    {
        // Kiểm tra đầu vào
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        // Lấy thông tin từ request
        $email = $request->input('email');
        $password = $request->input('password');

        // Kiểm tra email và mật khẩu
        $account = Account::with('phan_quyen','user')->where('email', $request->email)->first();
        if ($account && $password === $account->mat_khau) {
            // $user1 = User::find($account->ma_tk);
            return response()->json([
                'redirect' => 'http://localhost:5173',
                'account' => [
                    'ma_tk' => $account->ma_tk,
                    'email' => $account->email,
                    'ngay_tao' => $account->ngay_tao,
                    'password' => $account->mat_khau,
                    'ten_user' => $account->user->ten_user,
                    'avatar' => $account->user->anh_dai_dien,
                    'ma_quyen' =>  $account->phan_quyen->ma_phan_quyen,
                    'quyen' =>  $account->phan_quyen->ten_quyen_han,
                ],
            ]);
        } else {
                return response()->json(['message' => 'Thông tin đăng nhập không hợp lệ'], 401);
        }
    }
};
