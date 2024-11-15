<?php

namespace Database\Seeders;

use App\Models\Authentication;
use Illuminate\Database\Seeder;

class AuthenticationSeeder extends Seeder
{
    public function run()
    {
        // Tạo 10 phân quyền giả
        Authentication::factory()->count(3)->create();
    }
}
