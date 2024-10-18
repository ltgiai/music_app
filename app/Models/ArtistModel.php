<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtistModel extends Model
{
    use HasFactory;

    protected $table = 'artist';

    // Cho phép các cột có thể được gán giá trị hàng loạt
    protected $fillable = ['ma_tk', 'ten_artist', 'anh_dai_dien', 'tong_tien'];

    // Nếu bảng của bạn không có trường timestamps (created_at, updated_at)
    public $timestamps = false;

     // Thiết lập quan hệ 1 nghệ sĩ thuộc về 1 tài khoản
     public function belongsToOneAccount()
     {
         return $this->belongsTo(AccountModel::class, 'ma_tk', 'ma_tk');
     }
}
