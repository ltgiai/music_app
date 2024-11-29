<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisingContractModel extends Model
{
    use HasFactory;

    protected $table = 'hop_dong_quang_cao';
    protected $primaryKey = 'ma_hop_dong';
    protected $keyType = 'string';
    // Cho phép các cột có thể được gán giá trị hàng loạt
    protected $fillable = [
        'ma_hop_dong',
        'ma_quang_cao',
        'luot_phat',
        'doanh_thu',
        'ngay_hieu_luc',
        'ngay_hoan_thanh',
        'trang_thai'
    ];

    // Nếu bảng của bạn không có trường timestamps (created_at, updated_at)
    public $timestamps = false;

    public function relationship()
    {
        return [
            'advertiser' => $this->advertiser(),
            'advertisement' => $this->advertisement()
        ];
    }

    // Thiết lập quan hệ 1 quảng cáo thuộc về 1 nhà quảng cáo
    public function advertiser()
    {
        return $this->belongsTo(AdvertiserModel::class, 'ma_nqc', 'ma_nqc');
    }

    public function advertisement()
    {
        return $this->belongsTo(AdvertisementModel::class, 'ma_quang_cao', 'ma_quang_cao');
    }

    // Phương thức thêm hợp đồng quảng cáo
    public function createAdvertisingContract($data)
    {
        return self::create([
            'ma_hop_dong' => $data['ma_hop_dong'],
            'ma_quang_cao' => $data['ma_quang_cao'],
            'luot_phat' => $data['luot_phat'],
            'doanh_thu' => $data['doanh_thu'],
            'ngay_hieu_luc' => $data['ngay_hieu_luc'],
            'ngay_hoan_thanh' => $data['ngay_hoan_thanh']
        ]);
    }

    // Phương thức cập nhật hợp đồng quảng cáo
    public function updateAdvertisingContract($data)
    {
        return $this->update([
            'ma_hop_dong' => $data['ma_hop_dong'],
            'ma_quang_cao' => $data['ma_quang_cao'],
            'luot_phat' => $data['luot_phat'],
            'doanh_thu' => $data['doanh_thu'],
            'ngay_hieu_luc' => $data['ngay_hieu_luc'],
            'ngay_hoan_thanh' => $data['ngay_hoan_thanh']
        ]);
    }

    // Phương thức xóa hợp đồng quảng cáo
    public function deleteAdvertisingContract()
    {
        return $this->delete();
    }
}
