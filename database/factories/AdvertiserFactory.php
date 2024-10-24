<?php
namespace Database\Factories;

use App\Models\AdvertisertModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdvertiserFactory extends Factory
{
    protected $model = AdvertiserModel::class;

    public function definition()
    {
        return [
            'ma_nqc' => $this->faker->unique()->regexify('[A-Za-z0-9]{50}'),  // Mã quảng cáo không trùng lặp với 50 kí tự
            'ten_nqc' => $this->faker->text(50)
        ];
    }
}
