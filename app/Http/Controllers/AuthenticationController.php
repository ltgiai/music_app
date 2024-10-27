<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthenticationModel extends Model
{
    use HasFactory;

    protected $table = 'phan_quyen';

    // Các cột có thể gán giá trị hàng loạt
    protected $fillable = ['ma_phan_quyen', 'ten_quyen_han', 'ngay_tao', 'tinh_trang'];

    public $timestamps = false;

    // Thiết lập quan hệ 1 phân quyền có 1 tài khoản
    public function account()
    {
        return $this->hasOne(AccountModel::class, 'ma_phanquyen', 'ma_phanquyen');
    }
}
