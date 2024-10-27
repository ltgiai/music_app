<?php

namespace Database\Factories;

use App\Models\Artist;
use App\Models\ArtistModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArtistFactory extends Factory
{
    // Định nghĩa model tương ứng với factory
    protected $model = ArtistModel::class;

    // Định nghĩa các giá trị giả cho từng cột của bảng artist
    public function definition()
    {
        return [
            'ma_tk' => $this->faker->unique()->numberBetween(1, 1000),  // Mã tài khoản giả, không trùng lặp
            'ten_artist' => $this->faker->name(),  // Tên nghệ sĩ giả
            'anh_dai_dien' => $this->faker->imageUrl(200, 200, 'people', true, 'Artist'),  // URL hình đại diện giả
            'tong_tien' => $this->faker->randomFloat(2, 1000000, 10000000),  // Tổng tiền ngẫu nhiên từ 100 đến 10000
        ];
    }
}
 