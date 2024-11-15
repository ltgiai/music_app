<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Artist;
use Database\Factories\ArtistFactory;
use Illuminate\Database\Seeder;

class ArtistSeeder extends Seeder
{
    public function run()
    {
        // Tạo 50 tài khoản và cho mỗi tài khoản một nghệ sĩ
        ArtistFactory::factory(50)->create();
    }
}
