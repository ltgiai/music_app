<?php
namespace Database\Factories;

use App\Models\AdvertisertModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdvertiserFactory extends Factory
{
    protected $model = AdvertiserModel::class;

    public function definition()
    {
        return [
            'ma_nqc' => 'NQC' . str_pad($this->faker->unique()->numberBetween(0, 9999), 4, '0', STR_PAD_LEFT),
            'ten_nqc' => $this->faker->word(),
            'so_dien_thoai' => $this->faker->word()
        ];
    }
}
