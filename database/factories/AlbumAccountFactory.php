<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\AlbumAccountModel;

class AlbumAccountFactory extends Factory
{

    protected $model = AlbumAccountModel::class;

    public function definition(): array
    {
        return [
            'ma_tk' => $this->faker->randomNumber(8),
            'ma_album' => $this->faker->randomNumber(8),
        ];
    }
}