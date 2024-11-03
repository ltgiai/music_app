<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    
    use HasFactory;
    public $timestamps = false;
    protected $table = 'taikhoan';
    protected $primaryKey = 'ma_tk'; // Khóa chính
    protected $fillable = ['ma_tk','gmail','mat_khau', 'ngay_tao','trang_thai','ma_phan_quyen'];

    public function user()
    {
        return $this->hasOne(User::class, 'ma_tk');
    }
    public function artist()
    {
        return $this->hasOne(ArtistModel::class, 'ma_tk');
    }
    public function thong_bao()
    {
        return $this->hasMany(Notification::class, 'ma_tk');
    }
    public function phan_quyen()
    {
        return $this->belongsTo(Decentralization::class, 'ma_phan_quyen');
    }
    public function album_tai_khoan()
    {
        return $this->hasMany(AlbumAccountModel::class, 'ma_tk');
    }
    public function dang_ky_premium()
    {
        return $this->hasMany(VoucherRegisterModel::class, 'ma_tk');
    }
    public function playlist()
    {
        return $this->hasMany(PlaylistModel::class, 'ma_tk');
    }
    public function luot_thich_album()
    {
        return $this->belongsToMany(AlbumModel::class, 'luot_thich_album', 'ma_tk', 'ma_album');
    }
    public function binh_luan()
    {
        return $this->hasMany(CommentModel::class, 'ma_tk');
    }
    public function luot_thich_bai_hat()
    {
        return $this->belongsToMany(LikeSongModel::class, 'luot_thich_bai_hat', 'ma_tk', 'ma_bai_hat');
    }
}
