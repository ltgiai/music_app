<?php

namespace Database\Factories;
use App\Models\Notification;
use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition()
    {
        return [
            'ma_tb' => $this->faker->unique()->regexify('[A-Za-z0-9]{10}'), // Tạo ma_tb ngẫu nhiên
            'ma_tk' => Account::factory(), // Lấy ma_tk từ bảng taikhoan
            'ten_tb' => $this->faker->sentence(6), // Tạo tiêu đề thông báo ngẫu nhiên
            'noi_dung_tb' => $this->faker->text(200), // Tạo nội dung thông báo ngẫu nhiên

        ];
    }
}



