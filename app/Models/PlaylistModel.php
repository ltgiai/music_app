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
    protected $fillable = ['ma_playlist', 'ten_playlist', 'ma_tk', 'so_luong_bai_hat', 'hinh_anh'];
    public $timestamps = false;

    private $ma_playlist;
    private $ten_playlist;
    private $ma_tk;
    private $so_luong_bai_hat;
    private $hinh_anh;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->ma_playlist = $attributes['ma_playlist'] ?? null;
        $this->ten_playlist = $attributes['ten_playlist'] ?? null;
        $this->ma_tk = $attributes['ma_tk'] ?? null;
        $this->so_luong_bai_hat = $attributes['so_luong_bai_hat'] ?? 0;
        $this->hinh_anh = $attributes['hinh_anh'] ?? null;
    }

    // Gom các relationships vào một phương thức
    public function relationships()
    {
        return [
            'tai_khoan' => $this->belongsTo(Account::class, 'ma_tk', 'ma_tk'),
            // 'playlist_baihat' => $this->hasMany(Song)
        ];
    }

    public function addPlaylist($data)
    {
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

    public function deletePlaylist($ma_playlist)
    {
        $playlist = self::find($ma_playlist);
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
