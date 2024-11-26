<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FunctionalDetail extends Model
{
    
    use HasFactory;
    public $timestamps = false;
    protected $table = 'chi_tiet_phan_quyen';
    protected $fillable = ['ma_phan_quyen','ma_chuc_nang','xem','them','sua','xoa'];
    public function phan_quyen()
    {
        return $this->belongsTo(Decentralization::class, 'ma_phan_quyen'); 
    }

    public function chuc_nang()
    {
        return $this->belongsTo(Functionn::class, 'ma_chuc_nang');
    }
}
