<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\LikeAlbumModel;

class LikeAlbumFactory extends Factory
{

    protected $model = LikeAlbumModel::class;

    public function definition(): array
    {
        return [
            'ma_tk' => $this->faker->unique()->randomNumber(8),
            'ma_album' => $this->faker->unique()->randomNumber(8),
            'ngay_tao' => $this->faker->date(),
            'ngay_huy' => $this->faker->date(),
            'ngay_chinh_sua' => $this->faker->date(),
        ];
    }
}