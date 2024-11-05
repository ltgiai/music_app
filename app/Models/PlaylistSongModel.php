<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlaylistSongModel extends Model
{
    use HasFactory;
    
    protected $table = 'playlist_baihat';
    public $timestamps = false;
    protected $fillable = ['ma_bai_hat', 'ma_playlist'];

    protected static function newFactory()
    {
        return \Database\Factories\PlaylistSongFactory::new();
    }
    
    // public function playlist_song() {
    //     return $this->belongsToMany(PlaylistSongModel::class, 'playlist_song', 'playlist_id', 'song_id');
    // }

    // public function luot_thich_playlist_song() {
    //     return $this->hasMany(LikePlaylistSongModel::class, 'playlist_song_id');
    // }

    public function getMaBaiHat()
    {
        return $this->attributes['ma_bai_hat'];
    }

    public function setMaBaiHat($value)
    {
        $this->attributes['ma_bai_hat'] = $value;
    }

    public function getMaPlaylist()
    {
        return $this->attributes['ma_playlist'];
    }

    public function setMaPlaylist($value)
    {
        $this->attributes['ma_playlist'] = $value;
    }
   
}