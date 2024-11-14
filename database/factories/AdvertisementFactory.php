<?php
namespace Database\Factories;

use App\Models\AdvertisementModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdvertisementFactory extends Factory
{
    protected $model = AdvertisementModel::class;

    public function definition()
    {
        $date = now()->format('dmY');
        return [
            'ma_quang_cao' => 'QC' . str_pad($this->faker->unique()->numberBetween(0, 9999), 4, '0', STR_PAD_LEFT),
            'ten_quang_cao' => $this->faker->word(),  // Tên quảng cáo (tối đa 50 ký tự)
            'ngay_tao' => $this->faker->dateTime(),  // Ngày tạo định dạng dd-MM-yyyy
            'luot_phat_tich_luy' => 0,  // Lượt phát mặc định là số nguyên, ví dụ ban đầu là 0
            'hinh_anh' => $this->faker->imageUrl(),  // Hình ảnh quảng cáo=
            'trang_thai' => $this->faker->numberBetween(1, 9) 
            'ma_nqc' => AdvertiserModel::factory()
        ];
    }
}
