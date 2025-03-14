<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Account extends Model
{

    use HasFactory;


    public $timestamps = false;
    protected $table = 'tai_khoan';
    protected $primaryKey = 'ma_tk';
    protected $keyType = 'string';
    protected $fillable = ['ma_tk', 'token', 'email', 'mat_khau', 'ngay_tao', 'trang_thai', 'ma_phan_quyen'];


    public function user()
    {
        return $this->hasOne(User::class, 'ma_tk', 'ma_tk');
    }

    public function voucher(){
        return $this->belongsToMany(VoucherModel::class, 'dang_ky_premium', 'ma_tk', 'ma_goi')
                        ->withPivot('ngay_dang_ky', 'ngay_het_han');
    }

    public function thong_bao()
    {
        return $this->hasMany(Notification::class, 'ma_tk');
    }

    public function phan_quyen()
    {
        return $this->belongsTo(Decentralization::class, 'ma_phan_quyen');
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
        // return $this->hasMany(CommentModel::class, 'ma_tk');
    }

    public function luot_thich_bai_hat()
    {
        // return $this->belongsToMany(LikeSongModel::class, 'luot_thich_bai_hat', 'ma_tk', 'ma_bai_hat');
    }
}
