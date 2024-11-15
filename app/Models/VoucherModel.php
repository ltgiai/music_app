<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherModel extends Model
{
    
    use HasFactory;

    public $incrementing = false;
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
    public $timestamps = false;
    
    // Thiết lập quan hệ 1 voucher có nhiều voucher_register
    public function voucher_register()
    {
         return $this->hasMany(VoucherRegisterModel::class, 'ma_goi', 'ma_goi');
    }

    // Phương thức thêm voucher
    public function createVoucher($data)
    {
        return self::create([
            'ma_goi' => $data['ma_goi'],
            'ten_goi' => $data['ten_goi'],
            'thoi_han' => $data['thoi_han'],
            'gia_goi' => $data['gia_goi'],
            'doanh_thu' => $data['doanh_thu'],
            'mo_ta' => $data['mo_ta'],
            'trang_thai' => $data['trang_thai']
        ]);
    }

    // Phương thức cập nhật voucher
    public function updateVoucher($data)
    {
        return $this->update([
            'ten_goi' => $data['ten_goi'],
            'thoi_han' => $data['thoi_han'],
            'gia_goi' => $data['gia_goi'],
            'doanh_thu' => $data['doanh_thu'],
            'mo_ta' => $data['mo_ta'],
            'trang_thai' => $data['trang_thai']
        ]);
    }

    // Phương thức xóa voucher
    public function deleteVoucher()
    {
        return $this->delete();
    }
}