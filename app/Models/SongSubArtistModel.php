<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SongSubArtistModel extends Model
{
    use HasFactory;

    protected $table = 'bai_hat_subartist';

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['ma_subartist', 'ma_bai_hat'];
    public $timestamps = false;

    private $ma_subartist;
    private $ma_bai_hat;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->ma_subartist = $attributes['id'] ?? $this->generateCustomId();
        $this->ma_bai_hat = $attributes['ma_tk'] ?? null;
    }

    private function generateCustomId()
    {
        return 'ART' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    // Gom các relationships vào một phương thức
    public function relationships()
    {
        return [
            'subartist' => $this->belongsTo(ArtistModel::class, 'ma_subartist', 'ma_tk'),
        ];
    }

    public function addSongSubArtist($data)
    {
        $data['id'] = $data['id'] ?? $this->generateCustomId();
        return self::create($data);
    }

    public function updateSongSubArtist($id, $data)
    {
        $songSubArtist = self::find($id);
        if ($songSubArtist) {
            $songSubArtist->update($data);
            return $songSubArtist;
        }
        return null;
    }

    public function deleteSongSubArtist($id)
    {
        $songSubArtist = self::find($id);
        if ($songSubArtist) {
            return $songSubArtist->delete();
        }
        return false;
    }

    
    public function getMaSubArtist()  { return $this->ma_subartist; }
    public function getMaBaiHat() { return $this->ma_bai_hat; }
    
    public function setMaSubArtist($ma_subartist) { $this->ma_subartist = $ma_subartist; }
    public function setMaBaiHat($ma_bai_hat) { $this->ma_bai_hat = $ma_bai_hat; }

}
