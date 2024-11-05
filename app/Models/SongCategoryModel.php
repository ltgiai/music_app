<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SongCategoryModel extends Model
{
    use HasFactory;

    protected $table = 'chung_loai';

    public $timestamps = false;

    protected $fillable = [
        'ma_chung_loai',
        'ten_chung_loai',
        /*'sluong_the_loai',*/
    ];

    public function genres()
    {
        return $this->hasMany(GenreModel::class, 'ma_chung_loai');
    }
}
