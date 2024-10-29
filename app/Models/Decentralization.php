<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Decentralization extends Model
{
    
    use HasFactory;

    protected $table = 'phan_quyen';
    protected $primaryKey = 'ma_phan_quyen'; // Khóa chính
    protected $fillable = ['ten_quyen_han','ngay_tao','tinh_trang'];
    public function taikhoan()
    {
        return $this->hasMany(Account::class, 'ma_tk');
    }
    public function chuc_nang(){
        return $this->hasMany(Account::class, 'ma_phan_quyen');
        return $this->belongsToMany(Functionn::class, 'chi_tiet_phan_quyen', 'ma_chuc_nang', 'chuc_nang_id');
    }

}
