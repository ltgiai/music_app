<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Decentralization extends Model
{
    
    use HasFactory;
    public $timestamps = false;
    protected $table = 'phan_quyen';
    protected $primaryKey = 'ma_phan_quyen';
    protected $keyType = 'string';
    protected $fillable = ['ma_phan_quyen','ten_quyen_han','ngay_tao','tinh_trang'];
    public function tai_khoan()
    {
        return $this->hasMany(Account::class, 'ma_phan_quyen');
    }
    public function chuc_nang(){
        return $this->belongsToMany(Functionn::class, 'chi_tiet_phan_quyen', 'ma_phan_quyen', 'ma_chuc_nang')
                    ->withPivot('xem', 'them', 'sua', 'xoa');
    }

}
