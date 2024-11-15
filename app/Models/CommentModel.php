<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentModel extends Model
{
    use HasFactory;

    protected $table = 'binh_luan'; 

    protected $primaryKey = 'ma_comment'; 

    public $timestamps = false; 

    protected $fillable = [
        'ma_tk',
        'ma_bai_hat',
        'noi_dung',
        'ngay_tao',
    ];

    const CREATED_AT = 'ngay_tao';


    public function user()
    {
        return $this->belongsTo(User::class, 'ma_tk');
    }

    public function song()
    {
        return $this->belongsTo(SongModel::class, 'ma_bai_hat');
    }
}
