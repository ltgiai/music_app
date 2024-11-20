<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenreModel extends Model
{
    use HasFactory;

    protected $table = 'the_loai'; // Tên bảng
    protected $primaryKey = 'ma_the_loai'; // Khóa chính
    protected $keyType = 'string'; // Kiểu khóa chính
    public $timestamps = false; // Bảng không có cột timestamps

    protected $fillable = [
        'ma_the_loai', // Mã thể loại
        'ten_the_loai', // Tên thể loại
    ];

    /**
     * Quan hệ với bảng `theloai_baihat`
     */
    public function genreSongs()
    {
        return $this->hasMany(GenreSongModel::class, 'ma_the_loai', 'ma_the_loai');
    }

    /**
     * Quan hệ với bài hát thông qua bảng trung gian `theloai_baihat`
     */
    public function songs()
    {
        return $this->belongsToMany(
            SongModel::class,
            'theloai_baihat', // Tên bảng trung gian
            'ma_the_loai',    // Khóa ngoại trong bảng trung gian
            'ma_bai_hat'      // Khóa liên kết đến bảng `bai_hat`
        );
    }
}
