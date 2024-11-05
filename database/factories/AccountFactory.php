<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\AccountModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AccountFactory extends Factory
{
    protected $model = AccountModel::class;

    public function definition()
    {
        return [
            'ma_tk' => $this->faker->unique()->numberBetween(1, 1000),  // Tạo mã tài khoản duy nhất
            'gmail' => $this->faker->unique()->safeEmail(),  // Email giả
            'mat_khau' => bcrypt('password'),  // Mật khẩu giả đã được mã hóa
            'ngay_tao' => now(),  // Ngày tạo là thời gian hiện tại
            'trang_thai' => $this->faker->randomElement([0, 1]),  // Trạng thái ngẫu nhiên (0 hoặc 1)
            'ma_phanquyen' => $this->faker->numberBetween(1, 5),  // Tạo mã phân quyền từ 1 đến 5
        ];
    }
}
