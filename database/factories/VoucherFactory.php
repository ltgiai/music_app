<?php
namespace Database\Factories;

use App\Models\VoucherModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class VoucherFactory extends Factory
{
    protected $model = VoucherModel::class;

    public function definition()
    {
        return [
            'ma_goi' => 'ma_quang_cao' => 'PREMI' . str_pad($this->faker->unique()->numberBetween(0, 999), 3, '0', STR_PAD_LEFT),
            'ten_goi' => $this->faker->word(),
            'thoi_han' => $this->fake->numberBetween(1, 9),
            'gia_goi' => $this->faker->randomFloat(3, 0, 9999999.999),
            'doanh_thu' => $this->faker->randomFloat(3, 0, 9999999.999),
            'mo_ta' => $this->faker->wrord(),
            'trang_thai' => $this->faker->boolean()
        ];
    }
}
