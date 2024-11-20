<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'thong_bao';
    protected $primaryKey = 'ma_tb'; // Khóa chính
    protected $keyType = 'string';
    protected $fillable = ['ten_tb','noi_dung_tb','ma_tk'];
    public function taikhoan()
    {
        return $this->belongsTo(Account::class, 'ma_tk');
    }


}
