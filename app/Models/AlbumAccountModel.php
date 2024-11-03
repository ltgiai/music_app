<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlbumAccountModel extends Model
{

    protected $table = 'album_tai_khoan';
    public $timestamps = false;

    protected $fillable = [
        'ma_tk', 'ma_album'
    ];

    protected static function newFactory()
    {
        return \Database\Factories\AlbumAccountFactory::new();
    }

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
}
