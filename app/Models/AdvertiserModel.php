<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertiserModel extends Model
{   
    use HasFactory;

    public $incrementing = false;
    protected $table = 'nha_dang_ky_quang_cao';
    protected $primaryKey = 'ma_nqc';
    protected $keyType = 'string';
    protected $fillable = ['ma_nqc', 'ten_nqc', 'so_dien_thoai', 'trang_thai'];
    public $timestamps = false;

    // Thiết lập quan hệ 1 nhà quảng cáo có nhiều hợp đồng quảng cáo
    public function advertising_contract()
    {
         return $this->hasMany(AdvertisingContractModel::class, 'ma_nqc', 'ma_nqc');
    }

    // Phương thức thêm nhà quảng cáo
    public function createAdvertiser($data)
    {
        return self::create([
            'ma_nqc' => $data['ma_nqc'],
            'ten_nqc' => $data['ten_nqc'], 
            'so_dien_thoai' => $data['so_dien_thoai']
        ]);
    }

    // Phương thức cập nhật nhà quảng cáo
    public function updateAdvertiser($data)
    {
        return $this->update([
            'ma_nqc' => $data['ma_nqc'],
            'ten_nqc' => $data['ten_nqc'], 
            'so_dien_thoai' => $data['so_dien_thoai']
        ]);
    }

    // Phương thức xóa nhà quảng cáo
    public function deleteAdvertiser()
    {
        return $this->delete();
    }
}
