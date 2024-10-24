<?php
namespace Database\Factories;

use App\Models\AdvertisingContractModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdvertisingContractFactory extends Factory
{
    protected $model = AdvertisingContractModel::class;

    public function definition()
    {
        return [
            'ma_quang_cao' => $this->faker->text(50),
            'ma_nqc' => $this->faker->text(50), 
            'luot_phat' => 0, 
            'doanh_thu' => $this->faker->randomFloat(3, 0, 9999999.999),  // Số thập phân có 3 chữ số sau dấu phẩy, giá trị từ 0 đến 9.999.999,999
            'ngay_tao' => now()
            'ngay_thanh_toan' => $this->faker->dateTime()
        ];
    }
}
