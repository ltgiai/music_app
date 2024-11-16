<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlbumModel extends Model
{
    use HasFactory;
    
    protected $table = 'album';
    protected $primaryKey = 'ma_album';
    public $timestamps = false;
    protected $fillable = ['ma_album', 'ten_album', 'ngay_tao', 'hinh_anh', 'luot_yeu_thich', 'trang_thai', 'so_luong_bai_hat'];

    protected static function newFactory()
    {
        return \Database\Factories\AlbumFactory::new();
    }
    
    // public function album_tai_khoan() {
    //     return $this->belongsToMany(AlbumAccountModel::class, 'album_account', 'album_id', 'user_id');
    // }

    // public function luot_thich_album() {
    //     return $this->hasMany(LikeAlbumModel::class, 'album_id');
    // }

    public function getMaAlbum()
    {
        return $this->ma_album;
    }

    public function setMaAlbum($ma_album)
    {
        $this->ma_album = $ma_album;
    }

    public function getTenAlbum()
    {
        return $this->ten_album;
    }

    public function setTenAlbum($ten_album)
    {
        $this->ten_album = $ten_album;
    }

    public function getNgayTao()
    {
        return $this->ngay_tao;
    }

    public function setNgayTao($ngay_tao)
    {
        $this->ngay_tao = $ngay_tao;
    }

    public function getHinhAnh()
    {
        return $this->hinh_anh;
    }

    public function setHinhAnh($hinh_anh)
    {
        $this->hinh_anh = $hinh_anh;
    }

    public function getLuotYeuThich()
    {
        return $this->luot_yeu_thich;
    }

    public function setLuotYeuThich($luot_yeu_thich)
    {
        $this->luot_yeu_thich = $luot_yeu_thich;
    }

    public function getTrangThai()
    {
        return $this->trang_thai;
    }

    public function setTrangThai($trang_thai)
    {
        $this->trang_thai = $trang_thai;
    }

    public function getSoLuongBaiHat()
    {
        return $this->so_luong_bai_hat;
    }

    public function setSoLuongBaiHat($so_luong_bai_hat)
    {
        $this->so_luong_bai_hat = $so_luong_bai_hat;
    }
}
