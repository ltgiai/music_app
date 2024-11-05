<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenreModel extends Model
{
    use HasFactory;

    protected $table = 'the_loai';
    public $timestamps = false;

    protected $fillable = [
        'ma_the_loai',
        'ten_the_loai',
        'ma_chung_loai',
    ];

    public function category()
    {
        return $this->belongsTo(SongCategoryModel::class, 'ma_chung_loai');
    }

    public function songs()
    {
        return $this->belongsToMany(SongModel::class, 'theloai_baihat', 'ma_the_loai', 'ma_bai_hat');
    }
}
