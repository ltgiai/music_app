<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhiLuotNghe extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'phi_luot_nghe'; // Tên bảng
    protected $primaryKey = 'ma_phi'; // Khóa chính
    public $incrementing = false; // Nếu `ma_phi` không tự tăng
    protected $keyType = 'string'; // Nếu `ma_phi` là chuỗi
    protected $fillable = ['ma_phi', 'gia_tien_luot_nghe'];
}
