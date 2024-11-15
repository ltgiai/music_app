<?php
namespace Database\Factories;

use App\Models\Account;
use App\Models\VoucherModel;
use App\Models\VoucherRegisterModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class VoucherRegisterFactory extends Factory
{
    protected $model = VoucherRegisterModel::class;

    public function definition()
    {
        return [
            'ma_tk' => Account::factory(),
            'ma_goi' => VoucherModel::factory(),
            'ngay_dang_ky' => $this->faker->dateTime(),
            'ngay_het_han' => $this->faker->dateTime(),
            'tong_tien_thanh_toan' => $this->faker->randomFloat(3, 0, 9999999.999),
            'trang_thai' => $this->faker->numberBetween(1, 9)
        ];
    }
}
