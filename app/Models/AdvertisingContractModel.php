<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisingContractModel extends Model
{
    use HasFactory;

    protected $table = 'hopdongquangcao';

    // Cho phép các cột có thể được gán giá trị hàng loạt
    protected $fillable = ['ma_quang_cao', 'ma_npc', 'luot_phat', 'doanh_thu', 'ngay_tao', 'ngay_thanh_toan'];

    // Nếu bảng của bạn không có trường timestamps (created_at, updated_at)
    public $timestamps = false;

    // Khóa chính của bảng
    protected $primaryKey = 'ma_quang_cao';

    // Khóa chính không tự động tăng
    public $incrementing = false;

    // Khóa chính là kiểu chuỗi (varchar)
    protected $keyType = 'string';

     // Thiết lập quan hệ 1 quảng cáo thuộc về 1 nhà quảng cáo
     public function belongsToOneAdvertiser()
     {
         return $this->belongsTo(AdvertiserModel::class, 'ma_nqc', 'ma_nqc');
     }

    public function getMaQuangCao()
    {
         return $this->ma_quang_cao;
    }

    public function getMaNpc()
    {
         return $this->ma_npc;
    }

    public function getLuotPhat()
    {
         return $this->luot_phat;
    }

    public function getDoanhThu()
    {
         return $this->doanh_thu;
    }

    public function getNgayTao()
    {
         return $this->ngay_tao;
    }

    public function getNgayThanhToan()
    {
         return $this->ngay_thanh_toan;
    }

    public function setMaQuangCao($ma_quang_cao)
    {
         $this->ma_quang_cao = $ma_quang_cao;
    }

    public function setMaNpc($ma_npc)
    {
         $this->ma_npc = $ma_npc;
    }

    public function setLuotPhat($luot_phat)
    {
         $this->luot_phat = $luot_phat;
    }

    public function setDoanhThu($doanh_thu)
    {
         $this->doanh_thu = $doanh_thu;
    }

    public function setNgayTao($ngay_tao)
    {
         $this->ngay_tao = $ngay_tao;
    }

    public function setNgayThanhToan($ngay_thanh_toan)
    {
         $this->ngay_thanh_toan = $ngay_thanh_toan;
    }
}
