<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherRegisterModel extends Model
{
    use HasFactory;

    protected $table = 'dang_ky_premium';

    // Cho phép các cột có thể được gán giá trị hàng loạt
    protected $fillable = ['ma_tk', 'ma_goi', 'ngay_dang_ky', 'ngay_het_han', 'gia_goi', 'trang_thai'];

    // Nếu bảng của bạn không có trường timestamps (created_at, updated_at)
    public $timestamps = false;

    // Khóa chính của bảng
    protected $primaryKey = 'ma_tk';

    // Khóa chính không tự động tăng
    public $incrementing = false;

    // Khóa chính là kiểu chuỗi (varchar)
    protected $keyType = 'string';

     // Thiết lập quan hệ 1 quảng cáo thuộc về 1 nhà quảng cáo
    public function belongsToOneAdvertiser()
    {
        return $this->belongsTo(VoucherModel::class, 'ma_goi', 'ma_goi');
    }

    public function getMaTk()
    {
        return $this->ma_tk;
    }

    public function getMaGoi()
    {
        return $this->ma_goi;
    }

    public function getNgayDangKy()
    {
        return $this->ngay_dang_ky;
    }

    public function getNgayHetHan()
    {
        return $this->ngay_het_han;
    }

    public function getGiaGoi()
    {
        return $this->gia_goi;
    }

    public function getTrangThai()
    {
        return $this->trang_thai;
    }

    public function setMaTk($ma_tk)
    {
        $this->ma_tk = $ma_tk;
    }

    public function setMaGoi($ma_goi)
    {
        $this->ma_goi = $ma_goi;
    }

    public function setNgayDangKy($ngay_dang_ky)
    {
        $this->ngay_dang_ky = $ngay_dang_ky;
    }

    public function setNgayHetHan($ngay_het_han)
    {
        $this->ngay_het_han = $ngay_het_han;
    }

    public function setGiaGoi($gia_goi)
    {
        $this->gia_goi = $gia_goi;
    }

    public function setTrangThai($trang_thai)
    {
        $this->trang_thai = $trang_thai;
    }
}
