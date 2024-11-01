<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\AlbumModel;

class AlbumFactory extends Factory
{

    protected $model = AlbumModel::class;

    public function definition(): array
    {
        $date = now()->format('dmY');   
        return [
            'ma_album' => 'ALBUM' . $date . str_pad($this->faker->unique()->numberBetween(0, 9999), 4, '0', STR_PAD_LEFT),
            'ten_album' => $this->faker->word(),
            'ngay_tao' => $this->faker->dateTime(),
            'hinh_anh' => $this->faker->imageUrl(),
            'luot_yeu_thich' => $this->faker->numberBetween(0, 1000),
            'trang_thai' => $this->faker->boolean(),
            'so_luong_bai_hat' => $this->faker->numberBetween(0, 20),
        ];
    }
}