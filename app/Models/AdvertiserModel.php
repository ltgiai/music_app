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

    // Khóa chính của bảng
    protected $primaryKey = 'ma_nqc';

    // Khóa chính không tự động tăng
    public $incrementing = false;

    // Khóa chính là kiểu chuỗi (varchar)
    protected $keyType = 'string';

     // Thiết lập quan hệ 1 nhà quảng cáo có nhiều quảng cáo
    public function advertising_contract()
    {
         return $this->hasMany(AdvertisingContractModel::class, 'ma_nqc', 'ma_nqc');
    }

    public function getMaNqc()
    {
        return $this->ma_nqc;
    }

    public function getTenNqc()
    {
        return $this->ten_nqc;
    }

    public function setTenNqc($ten_nqc)
    {
        $this->ten_nqc = $ten_nqc;
    }

    public function setMaNqc($ma_nqc)
    {
        $this->ma_nqc = $ma_nqc;
    }
}
