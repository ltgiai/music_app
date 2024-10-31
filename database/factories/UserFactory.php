<?php

namespace Database\Factories;
use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserModel>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = User::class;
    
    public function definition()
    {   
        return [
            'ma_tk' => Account::factory(), 
            'ten_user' => $this->faker->name(), // Tạo tên người dùng ngẫu nhiên
            'anh_dai_dien' => $this->faker->text(50), // Tạo một chuỗi text ngẫu nhiên cho ảnh đại diện
        ];
    }
}
