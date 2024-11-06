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
            'ma_nqc' => 'ADVERTISER' . str_pad($this->faker->unique()->numberBetween(0, 999), 3, '0', STR_PAD_LEFT),
            'ten_nqc' => $this->faker->word(),
            'sdt' => $this->faker->text(10)
        ];
    }
}
