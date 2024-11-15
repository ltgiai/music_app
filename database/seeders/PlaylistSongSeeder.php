<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PlaylistSongModel;

class PlaylistSongSeeder extends Seeder
{
    public function run(): void
    {
        PlaylistSongModel::factory(5)->create();
    }
}
