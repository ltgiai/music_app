<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherRegisterModel extends Model
{
    use HasFactory;

    protected $table = 'dang_ky_premium';

    // Cho phép các cột có thể được gán giá trị hàng loạt
    protected $fillable = ['ma_tk', 'ma_goi', 'ngay_dang_ky', 'ngay_het_han', 'tong_tien_thanh_toan', 'trang_thai'];

    public $timestamps = false;

    public function voucher()
    {
        return $this->belongsTo(VoucherModel::class, 'ma_goi', 'ma_goi');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'ma_tk', 'ma_tk');
    }
}