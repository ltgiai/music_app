<?php

namespace Database\Factories;

use App\Models\Authentication;
use App\Models\AuthenticationModel;
use App\Models\PhanQuyen;
use Illuminate\Database\Eloquent\Factories\Factory;

class PhanQuyenFactory extends Factory
{
    protected $model = AuthenticationModel::class;

    public function definition()
    {
        return [
            'ma_phan_quyen' => $this->faker->unique()->numberBetween(1, 10),  // Mã phân quyền duy nhất
            'ten_quyen_han' => $this->faker->randomElement(['Admin', 'User', 'Artist']),  // Tên phân quyền
            'ngay_tao' => now(),
            'tinh_trang' => $this->faker->randomElement([0, 1]),
        ];
    }
}
