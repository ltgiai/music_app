<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AlbumAccountModel;

class ALbumAccountSeeder extends Seeder
{
    public function run()
    {
       AlbumAccountModel::factory()->count(10)->create();
    } 

}