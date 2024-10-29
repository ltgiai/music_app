<?php

namespace Database\Factories;
use App\Models\Account;
use App\Models\Decentralization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AccountModel>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Account::class;
    public function definition()
    {
        return [
            // 'ma_tk' => $this->faker->unique()->regexify('[A-Za-z0-9]{15}'), // Tạo ma_tk ngẫu nhiên
            'ma_tk' => $this->faker->unique()->bothify('MA###'), // Tạo mã tài khoản duy nhất
            'gmail' => $this->faker->unique()->safeEmail(), // Tạo email ngẫu nhiên
            'mat_khau' => $this->faker->text(50), // Mật khẩu mặc định
            'ngay_tao' => $this->faker->dateTimeBetween('-1 year', 'now'), // Ngày tạo ngẫu nhiên
            'trang_thai' => $this->faker->boolean(), // Trạng thái ngẫu nhiên
            'ma_phan_quyen' => $this->faker->randomElement(['PQ1', 'PQ2', 'PQ3']),  // ma_phan_quyen ngẫu nhiên, có thể null
        ];
    }
}
