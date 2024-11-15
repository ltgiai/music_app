<?php

namespace Database\Factories;
use App\Models\Decentralization;
use Illuminate\Database\Eloquent\Factories\Factory;

class DecentralizationFactory extends Factory
{
    protected $model = Decentralization::class;
    public function definition()
    {   
        static $counter = 1;
        return [
            'ma_phan_quyen' => 'PQ' . $counter++, // Tạo ma_phan_quyen ngẫu nhiên
            'ten_quyen_han' => $this->faker->text(50), // Tạo tên quyền hạn ngẫu nhiên
            'ngay_tao' => $this->faker->dateTimeBetween('-1 year', 'now'), // Ngày tạo ngẫu nhiên trong 1 năm qua
            'tinh_trang' => $this->faker->boolean(), // Tạo trạng thái ngẫu nhiên (0 hoặc 1)
        ];
    }
}
