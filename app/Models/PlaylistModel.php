<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlaylistModel extends Model
{
    use HasFactory;

    protected $table = 'playlist';

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['ma_playlist', 'ten_playlist', 'ma_tk', 'so_luong_bai_hat'];
    public $timestamps = false;

    private $ma_playlist;
    private $ten_playlist;
    private $ma_tk;
    private $so_luong_bai_hat;

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
        return 'PL' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
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

    public function addPlaylist($data)
    {
        $data['id'] = $data['id'] ?? $this->generateCustomId();
        return self::create($data);
    }

    public function updatePlaylist($id, $data)
    {
        $playlist = self::find($id);
        if ($playlist) {
            $playlist->update($data);
            return $playlist;
        }
        return null;
    }

    public function deletePlaylist($id)
    {
        $playlist = self::find($id);
        if ($playlist) {
            return $playlist->delete();
        }
        return false;
    }

    /**
     * Get the value of ma_playlist
     */ 
    public function getMa_playlist()
    {
        return $this->ma_playlist;
    }

    /**
     * Set the value of ma_playlist
     *
     * @return  self
     */ 
    public function setMa_playlist($ma_playlist)
    {
        $this->ma_playlist = $ma_playlist;

        return $this;
    }

    /**
     * Get the value of ten_playlist
     */ 
    public function getTen_playlist()
    {
        return $this->ten_playlist;
    }

    /**
     * Set the value of ten_playlist
     *
     * @return  self
     */ 
    public function setTen_playlist($ten_playlist)
    {
        $this->ten_playlist = $ten_playlist;

        return $this;
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
     * Get the value of so_luong_bai_hat
     */ 
    public function getSo_luong_bai_hat()
    {
        return $this->so_luong_bai_hat;
    }

    /**
     * Set the value of so_luong_bai_hat
     *
     * @return  self
     */ 
    public function setSo_luong_bai_hat($so_luong_bai_hat)
    {
        $this->so_luong_bai_hat = $so_luong_bai_hat;

        return $this;
    }
}
