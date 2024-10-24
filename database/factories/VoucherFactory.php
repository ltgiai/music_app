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
            'ma_goi' => $this->faker->unique()->regexify('[A-Za-z0-9]{50}'), 
            'ten_goi' => $this->faker->text(50),
            'thoi_han' => $this->fake->numberBetween(1, 9),  // Số nguyên từ 1 đến 9
            'gia_goi' => $this->faker->randomFloat(3, 0, 9999999.999),  // Số thập phân có 3 chữ số sau dấu phẩy, giá trị từ 0 đến 9.999.999,999
            'doanh_thu' => $this->faker->randomFloat(3, 0, 9999999.999),  // Số thập phân có 3 chữ số sau dấu phẩy, giá trị từ 0 đến 9.999.999,999
            'mo_ta' => $this->faker->text(),
            'trang_thai' => $this->fake->numberBetween(1, 9)
        ];
    }
}
