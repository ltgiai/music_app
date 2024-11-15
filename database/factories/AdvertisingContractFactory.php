<?php
namespace Database\Factories;

use App\Models\AdvertisingContractModel;
use App\Models\AdvertiserModel;
use App\Models\AdvertisementModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdvertisingContractFactory extends Factory
{
    protected $model = AdvertisingContractModel::class;

    public function definition()
    {
        $date = now()->format('dmY');
        return [
            'ma_hop_dong' => 'HD' . str_pad($this->faker->unique()->numberBetween(0, 9999), 4, '0', STR_PAD_LEFT),
            'ma_quang_cao' => AdvertisementModel::factory(), 
            'luot_phat' => 0, 
            'doanh_thu' => $this->faker->randomFloat(3, 0, 9999999.999),  // Số thập phân có 3 chữ số sau dấu phẩy, giá trị từ 0 đến 9.999.999,999
            'ngay_hieu_luc' => $this->faker->dateTime(),
            'ngay_hoan_thanh' => $this->faker->dateTime()
        ];
    }
}
