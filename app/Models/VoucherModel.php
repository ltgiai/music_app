<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherModel extends Model
{
    
    use HasFactory;
    public $timestamps = false;
    protected $table = 'goi_premium';
    protected $primaryKey = 'ma_goi';
    protected $keyType = 'string';
    
    // Cho phép các cột có thể được gán giá trị hàng loạt
    protected $fillable = [
        'ma_goi', 
        'ten_goi',
        'thoi_han', 
        'gia_goi', 
        'doanh_thu', 
        'mo_ta', 
        'trang_thai'];

    // Nếu bảng của bạn không có trường timestamps (created_at, updated_at)
    
    // Thiết lập quan hệ 1 voucher có nhiều voucher_register
    public function tai_khoan(){
        return $this->belongsToMany(Account::class, 'dang_ky_premium', 'ma_goi', 'ma_tk');
    }
}