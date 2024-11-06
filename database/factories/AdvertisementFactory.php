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
            'ma_quang_cao' => 'PR' . $date . str_pad($this->faker->unique()->numberBetween(0, 999), 3, '0', STR_PAD_LEFT),
            'ten_quang_cao' => $this->faker->word(),  // Tên quảng cáo (tối đa 50 ký tự)
            'ngay_tao' => $this->faker->dateTime(),  // Ngày tạo định dạng dd-MM-yyyy
            'ngay_huy' => $this->faker->dateTime(),  // Ngày hủy định dạng dd-MM-yyyy
            'luot_phat' => 0,  // Lượt phát mặc định là số nguyên, ví dụ ban đầu là 0
            'link_quang_cao' => $this->faker->url(),  // Link quảng cáo
            'trang_thai' => $this->faker->numberBetween(1, 9) 
        ];
    }
}
