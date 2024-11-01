<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LikeAlbumModel extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'luot_thich_album';
    protected $fillable = ['ma_tk', 'ma_album', 'ngay_tao', 'ngay_huy', 'ngay_chinh_sua'];

    protected static function newFactory()
    {
        return \Database\Factories\LikeAlbumFactory::new();
    }

    // public function album() {
    //     return $this->belongsTo(AlbumModel::class, 'album_id');
    // }

    // public function account() {
    //     return $this->belongsTo(account::class, 'user_id');
    // }

    public function getMaTk()
    {
        return $this->attributes['ma_tk'];
    }

    public function setMaTk($value)
    {
        $this->attributes['ma_tk'] = $value;
    }

    public function getMaAlbum()
    {
        return $this->attributes['ma_album'];
    }

    public function setMaAlbum($value)
    {
        $this->attributes['ma_album'] = $value;
    }

    public function getNgayTao()
    {
        return $this->attributes['ngay_tao'];
    }

    public function setNgayTao($value)
    {
        $this->attributes['ngay_tao'] = $value;
    }

    public function getNgayHuy()
    {
        return $this->attributes['ngay_huy'];
    }

    public function setNgayHuy($value)
    {
        $this->attributes['ngay_huy'] = $value;
    }

    public function getNgayChinhSua()
    {
        return $this->attributes['ngay_chinh_sua'];
    }

    public function setNgayChinhSua($value)
    {
        $this->attributes['ngay_chinh_sua'] = $value;
    }
}
