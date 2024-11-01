<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherModel extends Model
{
    
    use HasFactory;

    protected $table = 'goipremium';

    // Cho phép các cột có thể được gán giá trị hàng loạt
    protected $fillable = ['ma_goi', 'ten_goi', 'thoi_han', 'gia_goi', 'doanh_thu', 'mo_ta', 'trang_thai'];

    // Nếu bảng của bạn không có trường timestamps (created_at, updated_at)
    public $timestamps = false;

    // Khóa chính của bảng
    protected $primaryKey = 'ma_goi';

    // Khóa chính không tự động tăng
    public $incrementing = false;

    // Khóa chính là kiểu chuỗi (varchar)
    protected $keyType = 'string';

    // Thiết lập quan hệ 1 voucher có nhiều đăng ký premium
    public function voucher_register()
    {
         return $this->hasMany(VoucherRegisterModel::class, 'ma_goi', 'ma_goi');
    }

    public function getMaGoi()
    {
        return $this->ma_goi;
    }

    public function getTenGoi()
    {
        return $this->ten_goi;
    }

    public function getThoiHan()
    {
        return $this->thoi_han;
    }

    public function getGiaGoi()
    {
        return $this->gia_goi;
    }

    public function getDoanhThu()
    {
        return $this->doanh_thu;
    }

    public function getMoTa()
    {
        return $this->mo_ta;
    }

    public function getTrangThai()
    {
        return $this->trang_thai;
    }

    public function setMaGoi($ma_goi)
    {
        $this->ma_goi = $ma_goi;
    }

    public function setTenGoi($ten_goi)
    {
        $this->ten_goi = $ten_goi;
    }

    public function setThoiHan($thoi_han)
    {
        $this->thoi_han = $thoi_han;
    }

    public function setGiaGoi($gia_goi)
    {
        $this->gia_goi = $gia_goi;
    }

    public function setDoanhThu($doanh_thu)
    {
        $this->doanh_thu = $doanh_thu;
    }

    public function setMoTa($mo_ta)
    {
        $this->mo_ta = $mo_ta;
    }

    public function setTrangThai($trang_thai)
    {
        $this->trang_thai = $trang_thai;
    }
}
