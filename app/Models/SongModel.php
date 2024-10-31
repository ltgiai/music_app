<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SongModel extends Model
{
    use HasFactory;

    protected $table = 'bai_hat';

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['ma_bai_hat', 'ten_bai_hat', 'thoi_luong', 'trang_thai', 'luot_nghe',
                            'hinh_anh', 'album', 'link_bai_hat', 'ngay_phat_hanh', 'ma_artist', 'ma_phi_luot_nghe', 'doanh_thu'];
    public $timestamps = false;

    private $ma_bai_hat;
    private $ten_bai_hat;
    private $thoi_luong;
    private $trang_thai;
    private $luot_nghe;
    private $hinh_anh;
    private $album;
    private $link_bai_hat;
    private $ngay_phat_hanh;
    private $ma_artist;
    private $ma_phi_luot_nghe;
    private $doanh_thu;
    
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->id = $attributes['id'] ?? $this->generateCustomId();
        $this->ma_tk = $attributes['ma_tk'] ?? null;
        $this->ten_artist = $attributes['ten_artist'] ?? null;
        $this->anh_dai_dien = $attributes['anh_dai_dien'] ?? null;
        $this->tong_tien = $attributes['tong_tien'] ?? 0;
    }

    private function generateCustomId()
    {
        return 'SONG' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    // Gom các relationships vào một phương thức
    public function relationships()
    {
        return [
            'tai_khoan' => $this->belongsTo(AccountModel::class, 'ma_tk', 'ma_tk'),
            'phieu_rut_tien_artist' => $this->hasMany(ArtistWithdrawalSlipModel::class, 'ma_tk', 'ma_t`k_artist'),
            'bai_hat_subartist' => $this->hasMany(SongSubArtistModel::class, 'ma_tk', 'ma_subartist'),
        ];
    }

    public function addSong($data)
    {
        $data['id'] = $data['id'] ?? $this->generateCustomId();
        return self::create($data);
    }

    public function updateSong($id, $data)
    {
        $song = self::find($id);
        if ($song) {
            $song->update($data);
            return $song;
        }
        return null;
    }

    public function deleteSong($id)
    {
        $song = self::find($id);
        if ($song) {
            return $song->delete();
        }
        return false;
    }

    /**
     * Get the value of ma_bai_hat
     */ 
    public function getMa_bai_hat()
    {
        return $this->ma_bai_hat;
    }

    /**
     * Set the value of ma_bai_hat
     *
     * @return  self
     */ 
    public function setMa_bai_hat($ma_bai_hat)
    {
        $this->ma_bai_hat = $ma_bai_hat;

        return $this;
    }

    /**
     * Get the value of ten_bai_hat
     */ 
    public function getTen_bai_hat()
    {
        return $this->ten_bai_hat;
    }

    /**
     * Set the value of ten_bai_hat
     *
     * @return  self
     */ 
    public function setTen_bai_hat($ten_bai_hat)
    {
        $this->ten_bai_hat = $ten_bai_hat;

        return $this;
    }


    /**
     * Get the value of thoi_luong
     */ 
    public function getThoi_luong()
    {
        return $this->thoi_luong;
    }

    /**
     * Set the value of thoi_luong
     *
     * @return  self
     */ 
    public function setThoi_luong($thoi_luong)
    {
        $this->thoi_luong = $thoi_luong;

        return $this;
    }

    /**
     * Get the value of trang_thai
     */ 
    public function getTrang_thai()
    {
        return $this->trang_thai;
    }

    /**
     * Set the value of trang_thai
     *
     * @return  self
     */ 
    public function setTrang_thai($trang_thai)
    {
        $this->trang_thai = $trang_thai;

        return $this;
    }

    /**
     * Get the value of luot_nghe
     */ 
    public function getLuot_nghe()
    {
        return $this->luot_nghe;
    }

    /**
     * Set the value of luot_nghe
     *
     * @return  self
     */ 
    public function setLuot_nghe($luot_nghe)
    {
        $this->luot_nghe = $luot_nghe;

        return $this;
    }

    /**
     * Get the value of hinh_anh
     */ 
    public function getHinh_anh()
    {
        return $this->hinh_anh;
    }

    /**
     * Set the value of hinh_anh
     *
     * @return  self
     */ 
    public function setHinh_anh($hinh_anh)
    {
        $this->hinh_anh = $hinh_anh;

        return $this;
    }


    /**
     * Get the value of album
     */ 
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * Set the value of album
     *
     * @return  self
     */ 
    public function setAlbum($album)
    {
        $this->album = $album;

        return $this;
    }

    /**
     * Get the value of link_bai_hat
     */ 
    public function getLink_bai_hat()
    {
        return $this->link_bai_hat;
    }

    /**
     * Set the value of link_bai_hat
     *
     * @return  self
     */ 
    public function setLink_bai_hat($link_bai_hat)
    {
        $this->link_bai_hat = $link_bai_hat;

        return $this;
    }

    /**
     * Get the value of ngay_phat_hanh
     */ 
    public function getNgay_phat_hanh()
    {
        return $this->ngay_phat_hanh;
    }

    /**
     * Set the value of ngay_phat_hanh
     *
     * @return  self
     */ 
    public function setNgay_phat_hanh($ngay_phat_hanh)
    {
        $this->ngay_phat_hanh = $ngay_phat_hanh;

        return $this;
    }

    /**
     * Get the value of ma_artist
     */ 
    public function getMa_artist()
    {
        return $this->ma_artist;
    }

    /**
     * Set the value of ma_artist
     *
     * @return  self
     */ 
    public function setMa_artist($ma_artist)
    {
        $this->ma_artist = $ma_artist;

        return $this;
    }

    /**
     * Get the value of ma_phi_luot_nghe
     */ 
    public function getMa_phi_luot_nghe()
    {
        return $this->ma_phi_luot_nghe;
    }

    /**
     * Set the value of ma_phi_luot_nghe
     *
     * @return  self
     */ 
    public function setMa_phi_luot_nghe($ma_phi_luot_nghe)
    {
        $this->ma_phi_luot_nghe = $ma_phi_luot_nghe;

        return $this;
    }

    /**
     * Get the value of doanh_thu
     */ 
    public function getDoanh_thu()
    {
        return $this->doanh_thu;
    }

    /**
     * Set the value of doanh_thu
     *
     * @return  self
     */ 
    public function setDoanh_thu($doanh_thu)
    {
        $this->doanh_thu = $doanh_thu;

        return $this;
    }
}
