<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayPerStreamModel extends Model
{
    use HasFactory;

    protected $table = 'artist';

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['ma_phi', 'ma_tk', 'ten_artist', 'anh_dai_dien', 'tong_tien'];
    public $timestamps = false;

    private $ma_tk;
    private $ten_artist;
    private $anh_dai_dien;
    private $tong_tien;

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
        return 'ART' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    // Gom các relationships vào một phương thức
    public function relationships()
    {
        return [
            'tai_khoan' => $this->belongsTo(Account::class, 'ma_tk', 'ma_tk'),
            'phieu_rut_tien_artist' => $this->hasMany(ArtistWithdrawalSlipModel::class, 'ma_tk', 'ma_tk_artist'),
            'bai_hat_subartist' => $this->hasMany(SongSubArtistModel::class, 'ma_tk', 'ma_subartist'),
        ];
    }

    public function addArtist($data)
    {
        $data['id'] = $data['id'] ?? $this->generateCustomId();
        return self::create($data);
    }

    public function updateArtist($id, $data)
    {
        $artist = self::find($id);
        if ($artist) {
            $artist->update($data);
            return $artist;
        }
        return null;
    }

    public function deleteArtist($id)
    {
        $artist = self::find($id);
        if ($artist) {
            return $artist->delete();
        }
        return false;
    }

    /**
     * Get the value of ma_tk
     */ 
    public function getMa_tk()
    {
        return $this->ma_tk;
    }

    /**
     * Set the value of ma_tk
     *
     * @return  self
     */ 
    public function setMa_tk($ma_tk)
    {
        $this->ma_tk = $ma_tk;

        return $this;
    }

    /**
     * Get the value of ten_artist
     */ 
    public function getTen_artist()
    {
        return $this->ten_artist;
    }

    /**
     * Set the value of ten_artist
     *
     * @return  self
     */ 
    public function setTen_artist($ten_artist)
    {
        $this->ten_artist = $ten_artist;

        return $this;
    }

    /**
     * Get the value of anh_dai_dien
     */ 
    public function getAnh_dai_dien()
    {
        return $this->anh_dai_dien;
    }

    /**
     * Set the value of anh_dai_dien
     *
     * @return  self
     */ 
    public function setAnh_dai_dien($anh_dai_dien)
    {
        $this->anh_dai_dien = $anh_dai_dien;

        return $this;
    }

    /**
     * Get the value of tong_tien
     */ 
    public function getTong_tien()
    {
        return $this->tong_tien;
    }

    /**
     * Set the value of tong_tien
     *
     * @return  self
     */ 
    public function setTong_tien($tong_tien)
    {
        $this->tong_tien = $tong_tien;

        return $this;
    }
}
