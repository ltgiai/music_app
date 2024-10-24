<?php
namespace Database\Factories;

use App\Models\VoucherRegisterModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class VoucherRegisterFactory extends Factory
{
    protected $model = VoucherRegisterModel::class;

    public function definition()
    {
        return [
            'ma_tk' => this->faker->text(50),
            'ma_goi' => this->faker->text(50),
            'ngay_dang_ky' => $this->faker->dateTime(),
            'ngay_het_han' => $this->faker->dateTime(),
            'gia_goi' => $this->faker->randomFloat(3, 0, 9999999.999),
            'trang_thai' => $this->faker->numberBetween(1, 9)
        ];
    }
}
