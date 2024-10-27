<?php

namespace app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlbumModel extends Model
{
    use HasFactory;
    
    protected $table = 'album';
    protected $primaryKey = 'ma_album';
    public $timestamps = false;
    protected $fillable = ['ma_album', 'ten_album', 'ngay_tao', 'hinh_anh', 'luot_yeu_thich', 'trang_thai', 'so_luong_bai_hat'];

    // public function luot_thich_album() {
    //     return $this->hasMany(LikeAlbumModel::class, 'album_id');
    // }

    // public function album_tai_khoan() {
    //     return $this->belongsToMany(AlbumAccountModel::class, 'album_account', 'album_id', 'user_id');
    // }
}