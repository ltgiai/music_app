<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenreSongModel extends Model
{
    use HasFactory;

    protected $table = 'theloai_baihat';
    public $timestamps = false;

    protected $fillable = [
        'ma_bai_hat',
        'ma_the_loai',
    ];

    public function genre()
    {
        return $this->belongsTo(GenreModel::class, 'ma_the_loai');
    }

    public function song()
    {
        return $this->belongsTo(SongModel::class, 'ma_bai_hat');
    }
}
