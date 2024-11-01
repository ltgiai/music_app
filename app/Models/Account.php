<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    
    use HasFactory;

    protected $table = 'taikhoan';
    protected $primaryKey = 'ma_tk'; // Khóa chính
    protected $fillable = ['gmail','mat_khau', 'ngay_tao','trang_thai','ma_phan_quyen'];

    public function user()
    {
        return $this->hasOne(User::class, 'ma_tk');
    }

    public function thong_bao()
    {
        return $this->hasMany(Notification::class, 'ma_tk');
    }
    public function phan_quyen()
    {
        return $this->belongsTo(Decentralization::class, 'ma_phan_quyen');
    }
}
