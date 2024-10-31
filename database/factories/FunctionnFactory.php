<?php

namespace Database\Factories;
use App\Models\Functionn;
use Illuminate\Database\Eloquent\Factories\Factory;

class FunctionnFactory extends Factory
{
    protected $model = Functionn::class;
    public function definition()
    {   
        static $counter = 1;
        return [
            'ma_chuc_nang' => 'CN' . $counter++, // Tạo ma_phan_quyen ngẫu nhiên
            'ten_chuc_nang' => $this->faker->text(50), // Tạo tiêu đề thông báo ngẫu nhiên
        ];
    }
}