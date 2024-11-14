<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AlbumModel;

class AlbumSeeder extends Seeder
{
    public function run(): void
    {
        AlbumModel::factory(5)->create();
    }
}