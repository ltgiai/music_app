<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'user';
    protected $primaryKey = 'ma_tk';
    protected $fillable = ['ma_tk','ten_user','anh_dai_dien'];
    public function tai_khoan()
    {
        return $this->belongsTo(Account::class, 'ma_tk');
    }
}
