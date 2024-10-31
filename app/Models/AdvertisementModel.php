<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisementModel extends Model
{
    
    use HasFactory;

    public $incrementing = false;
    protected $table = 'quangcao';
    protected $primaryKey = 'ma_quang_cao';
    protected $keyType = 'string';
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
    public $timestamps = false;
    
    // Thiết lập quan hệ 1 quảng cáo có nhiều hợp đồng quảng cáo
    public function advertising_contract()
    {
         return $this->hasMany(AdvertisingContractModel::class, 'ma_quang_cao', 'ma_quang_cao');
    }

    public function createAdvertisement($data)
    {
        return self::create([
            'ma_quang_cao' => $data['ma_quang_cao'],
            'ten_quang_cao' => $data['ten_quang_cao'],
            'ngay_tao' => $data['ngay_tao'],
            'ngay_huy' => $data['ngay_huy'],
            'luot_phat' => $data['luot_phat'],
            'link_quang_cao' => $data['link_quang_cao'],
            'trang_thai' => $data['trang_thai']
        ]);
    }

    public function updateAdvertisement($data)
    {
        return $this->update([
            'ten_quang_cao' => $data['ten_quang_cao'],
            'ngay_tao' => $data['ngay_tao'],
            'ngay_huy' => $data['ngay_huy'],
            'luot_phat' => $data['luot_phat'],
            'link_quang_cao' => $data['link_quang_cao'],
            'trang_thai' => $data['trang_thai']
        ]);
    }

    public function deleteAdvertisement()
    {
        return $this->delete();
    }
}