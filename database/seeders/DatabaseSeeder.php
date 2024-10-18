<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Account;
use App\Models\AccountModel;
use App\Models\Artist;
use App\Models\PhanQuyen;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Tắt kiểm tra ràng buộc khóa ngoại
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // Artist::factory(50)->create();
        AccountModel::factory(50)->create();

        // Bật lại kiểm tra ràng buộc khóa ngoại
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
