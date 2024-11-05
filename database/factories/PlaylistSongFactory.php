<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\PlaylistSongModel;

class PlaylistSongFactory extends Factory // ??? need help here @@
{

    protected $model = PlaylistSongModel::class;

    public function definition(): array
    {
        return [
            'ma_bai_hat' => $this->faker->word(),
            'ma_playlist' => $this->faker->word(),
        ];
    }
}