<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertiserModel extends Model
{   
    use HasFactory;

    protected $table = 'nhadangkyquangcao';

    // Cho phép các cột có thể được gán giá trị hàng loạt
    protected $fillable = ['ma_nqc', 'ten_nqc'];

    // Nếu bảng của bạn không có trường timestamps (created_at, updated_at)
    public $timestamps = false;

    // Thiết lập quan hệ 1 nhà quảng cáo có nhiều quảng cáo
    public function advertising_contract()
    {
         return $this->hasMany(AdvertisingContractModel::class, 'ma_nqc', 'ma_nqc');
    }

    // Phương thức thêm nhà quảng cáo
    public function createAdvertiser($data)
    {
        return self::create([
            'ma_nqc' => $data['ma_nqc'],
            'ten_nqc' => $data['ten_nqc']
        ]);
    }

    // Phương thức cập nhật nhà quảng cáo
    public function updateAdvertiser($data)
    {
        return $this->update([
            'ma_nqc' => $data['ma_nqc'],
            'ten_nqc' => $data['ten_nqc']
        ]);
    }

    // Phương thức xóa nhà quảng cáo
    public function deleteAdvertiser()
    {
        return $this->delete();
    }
}