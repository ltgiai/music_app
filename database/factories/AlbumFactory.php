<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;
use App\Models\AlbumModel;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AlbulModel>
 */
class AlbumFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ma_album' => $this->faker->unique()->randomNumber(8),
            'ten_album' => $this->faker->word(),
            'ngay_tao' => $this->faker->dateTime(),
            'hinh_anh' => $this->faker->imageUrl(),
            'luot_yeu_thich' => $this->faker->numberBetween(0, 1000),
            'trang_thai' => $this->faker->boolean(),
            'so_luong_bai_hat' => $this->faker->numberBetween(0, 20),
        ];
    }
}