<?php
namespace Database\Factories;

use App\Models\AdvertisementModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdvertisementFactory extends Factory
{
    protected $model = AdvertisementModel::class;

    public function definition()
    {
        return [
            'ma_quang_cao' => $this->faker->unique()->regexify('[A-Za-z0-9]{50}'),  // Mã quảng cáo không trùng lặp với 50 ký tự
            'ten_quang_cao' => $this->faker->text(50),  // Tên quảng cáo (tối đa 50 ký tự)
            'ngay_tao' => now(),  // Ngày tạo định dạng dd-MM-yyyy
            'ngay_huy' => $this->faker->dateTime(),  // Ngày hủy định dạng dd-MM-yyyy
            'luot_phat' => 0,  // Lượt phát mặc định là số nguyên, ví dụ ban đầu là 0
            'link_quang_cao' => $this->faker->url(),  // Link quảng cáo
            'trang_thai' => $this->faker->numberBetween(1, 9) // Chỉnh sửa từ 'fake' thành 'faker'
        ];
    }
}
