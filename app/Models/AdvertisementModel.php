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