<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SongQualityModel extends Model
{
    use HasFactory;

    protected $table = 'chat_luong_bai_hat';

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'ma_bai_hat',
        'chat_luong',
        'link_bai_hat',
    ];
    
    public $timestamps = false;

    private $ma_bai_hat;
    private $chat_luong;
    private $link_bai_hat;
    
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Khởi tạo giá trị mặc định cho các thuộc tính nếu không có trong $attributes
        $this->ma_bai_hat = $attributes['ma_bai_hat'] ?? null;
        $this->chat_luong = $attributes['chat_luong'] ?? null;
        $this->link_bai_hat = $attributes['link_bai_hat'] ?? null;
    }


    // Gom các relationships vào một phương thức
    public function relationships()
    {
        return [
            'bai_hat' => $this->belongsTo(SongModel::class, 'ma_bai_hat', 'ma_bai_hat'),
        ];
    }

    public function addSongQuality($data)
    {
        $data['id'] = $data['id'] ?? null;
        return self::create($data);
    }

    public function updateSongQuality($id, $data)
    {
        $song = self::find($id);
        if ($song) {
            $song->update($data);
            return $song;
        }
        return null;
    }

    public function deleteSongQuality($id)
    {
        $song = self::find($id);
        if ($song) {
            return $song->delete();
        }
        return false;
    }
}
