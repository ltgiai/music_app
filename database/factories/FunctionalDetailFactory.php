<?php

namespace Database\Factories;
use App\Models\FunctionalDetail;
use App\Models\Decentralization;
use App\Models\Functionn;
use Illuminate\Database\Eloquent\Factories\Factory;

class FunctionalDetailFactory extends Factory
{
    protected $model = FunctionalDetailModel::class;

    public function definition()
    {
        return [
            'ma_phan_quyen' => $this->faker->optional()->randomElement(['PQ1', 'PQ2', 'PQ3']),
            'ma_chuc_nang' => $this->faker->optional()->randomElement(['CN1', 'CN2', 'CN3','CN4','CN5']), // Tạo tiêu đề thông báo ngẫu nhiên
            'mo_ta_vai_tro' => $this->faker->text(200), // Tạo nội dung thông báo ngẫu nhiên
        ];
    }
}
