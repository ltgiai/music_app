<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountModel extends Model
{
    
    use HasFactory;

    protected $table = 'tai_khoan';

    // Cho phép các cột có thể được gán giá trị hàng loạt
    protected $fillable = ['ma_tk', 'gmail', 'mat_khau', 'ngay_tao', 'trang_thai', 'ma_phanquyen'];

    // Nếu bảng của bạn không có trường timestamps (created_at, updated_at)
    public $timestamps = false;

     // Thiết lập quan hệ 1 tài khoản có 1 nghệ sĩ
     public function artist()
     {
         return $this->hasOne(ArtistModel::class, 'ma_tk', 'ma_tk');
     }
}
