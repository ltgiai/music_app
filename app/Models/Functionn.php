<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Functionn extends Model
{
    
    use HasFactory;
    public $timestamps = false;
    protected $table = 'chuc_nang';
    protected $primaryKey = 'ma_chuc_nang'; // Khóa chính
    protected $keyType = 'string';
    protected $fillable = ['ma_chuc_nang','ten_chuc_nang'];
    
    public function phan_quyen()
    {
        return $this->belongsToMany(Decentralization::class, 'chi_tiet_phan_quyen', 'ma_chuc_nang', 'ma_phan_quyen')
                    ->withPivot('xem', 'them', 'sua', 'xoa');
    }
}
