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

    // Nếu bảng của bạn không có trường timestamps (created_at, updated_at)
    public $timestamps = false;

    public function relationship()
    {
        return [
            'voucher' => $this->voucher(),
            'account' => $this->account(),
        ];
    }

    // Thiết lập quan hệ 1 voucher_register thuộc về 1 voucher
    public function voucher()
    {
        return $this->belongsTo(VoucherModel::class, 'ma_goi', 'ma_goi');
    }

    // Thiết lập quan hệ 1 voucher_register thuộc về 1 account
    public function account()
    {
        return $this->belongsTo(Account::class, 'ma_tk', 'ma_tk');
    }

    // Phương thức thêm voucher_register
    public function createVoucherRegister($data)
    {
        return self::create([
            'ma_tk' => $data['ma_tk'],
            'ma_goi' => $data['ma_goi'],
            'ngay_dang_ky' => $data['ngay_dang_ky'],
            'ngay_het_han' => $data['ngay_het_han'],
            'tong_tien_thanh_toan' => $data['tong_tien_thanh_toan'],
            'trang_thai' => $data['trang_thai']
        ]);
    }

    // Phương thức cập nhật voucher_register
    public function updateVoucherRegister($data)
    {
        return $this->update([
            'ma_tk' => $data['ma_tk'],
            'ma_goi' => $data['ma_goi'],
            'ngay_dang_ky' => $data['ngay_dang_ky'],
            'ngay_het_han' => $data['ngay_het_han'],
            'tong_tien_thanh_toan' => $data['tong_tien_thanh_toan'],
            'trang_thai' => $data['trang_thai']
        ]);
    }

    // Phương thức xóa voucher_register
    public function deleteVoucherRegister()
    {
        return $this->delete();
    }
}