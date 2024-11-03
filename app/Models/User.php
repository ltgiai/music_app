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
    protected $fillable = ['ten_user','anh_dai_dien'];
    public function taikhoan()
    {
        return $this->belongsTo(Account::class, 'ma_tk');
    }
}
