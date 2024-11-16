<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisementModel extends Model
{
    
    use HasFactory;

    protected $table = 'quangcao';

    // Cho phép các cột có thể được gán giá trị hàng loạt
    protected $fillable = [
        'ma_quang_cao', 
        'ten_quang_cao', 
        'ngay_tao', 
        'ngay_huy', 
        'luot_phat', 
        'link_quang_cao', 
        'trang_thai'
    ];

    // Nếu bảng của bạn không có trường timestamps (created_at, updated_at)
    public $timestamps = false;

    // Khóa chính của bảng
    protected $primaryKey = 'ma_quang_cao';

    // Khóa chính không tự động tăng
    public $incrementing = false;

    // Khóa chính là kiểu chuỗi (varchar)
    protected $keyType = 'string';

    public function getMaQuangCao()
    {
        return $this->ma_quang_cao;
    }

    public function getTenQuangCao()
    {
        return $this->ten_quang_cao;
    }

    public function getNgayTao()
    {
        return $this->ngay_tao;
    }

    public function getNgayHuy()
    {
        return $this->ngay_huy;
    }

    public function getLuotPhat()
    {
        return $this->luot_phat;
    }

    public function getLinkQuangCao()
    {
        return $this->link_quang_cao;
    }

    public function getTrangThai()
    {
        return $this->trang_thai;
    }

    public function setMaQuangCao($ma_quang_cao)
    {
        $this->ma_quang_cao = $ma_quang_cao;
    }

    public function setTenQuangCao($ten_quang_cao)
    {
        $this->ten_quang_cao = $ten_quang_cao;
    }

    public function setNgayTao($ngay_tao)
    {
        $this->ngay_tao = $ngay_tao;
    }

    public function setNgayHuy($ngay_huy)
    {
        $this->ngay_huy = $ngay_huy;
    }

    public function setLuotPhat($luot_phat)
    {
        $this->luot_phat = $luot_phat;
    }

    public function setLinkQuangCao($link_quang_cao)
    {
        $this->link_quang_cao = $link_quang_cao;
    }

    public function setTrangThai($trang_thai)
    {
        $this->trang_thai = $trang_thai;
    }
}