<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Account;
use App\Models\User;
use App\Models\Functionn;
use App\Models\Decentralization;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Tạo 10 tài khoản mới
        // $accounts = Account::factory()->count(10)->create();
        // Functionn::factory()->count(5)->create();
        // Decentralization::factory()->count(3)->create();
        // // Tạo người dùng cho mỗi tài khoản đã tồn tại
        // foreach ($accounts as $account) {
        //     User::factory()->create([
        //         'ma_tk' => $account->ma_tk, // Gán ma_tk từ tài khoản
        //     ]);
        // }
        // Account::factory()
        //     ->count(5) // Số lượng tài khoản muốn tạo
        //     ->create()
        //     ->each(function ($account) {
        //         // Tạo user tương ứng với mỗi tài khoản
        //         User::factory()
        //             ->create(['ma_tk' => $account->ma_tk]); // Gán ma_tk của tài khoản
        //     });
    }
}