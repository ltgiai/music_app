<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LikeSongModel extends Model
{
    use HasFactory;

    protected $table = 'luot_thich_bai_hat';
    public $timestamps = false;


    protected $fillable = [
        'ma_tk',
        'ma_bai_hat',
        'ngay_thich',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'ma_tk');
    }

    public function song()
    {
        return $this->belongsTo(SongModel::class, 'ma_bai_hat');
    }
}