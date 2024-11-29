<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SoftDeletes;

class SongModel extends Model
{
    use HasFactory;

    protected $table = 'bai_hat';

    protected $dates = ['deleted_at']; // Trường này sẽ được thêm vào bảng

    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'ma_bai_hat';
    protected $fillable = [
        'ma_bai_hat',
        'ten_bai_hat',
        'ma_tk_artist',
        'ma_album',
        'ma_phi_luot_nghe',
        'thoi_luong',
        'trang_thai',
        'luot_nghe',
        'hinh_anh',
        'ngay_phat_hanh',
        'doanh_thu'
    ];
    public $timestamps = false;

    private $ma_bai_hat;
    private $ten_bai_hat;
    private $ma_tk_artist;
    private $ma_album;
    private $ma_phi_luot_nghe;
    private $thoi_luong;
    private $trang_thai;
    private $luot_nghe;
    private $hinh_anh;
    private $ngay_phat_hanh;
    private $doanh_thu;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Khởi tạo giá trị mặc định cho các thuộc tính nếu không có trong $attributes
        $this->ma_bai_hat = $attributes['ma_bai_hat'] ?? $this->generateCustomId();
        $this->ten_bai_hat = $attributes['ten_bai_hat'] ?? null;
        $this->ma_tk_artist = $attributes['ma_tk_artist'] ?? null;
        $this->ma_album = $attributes['ma_album'] ?? null;
        $this->ma_phi_luot_nghe = $attributes['ma_phi_luot_nghe'] ?? null;
        $this->thoi_luong = $attributes['thoi_luong'] ?? null;
        $this->trang_thai = $attributes['trang_thai'] ?? 1; // Mặc định là '1' (hoạt động)
        $this->luot_nghe = $attributes['luot_nghe'] ?? 0;
        $this->hinh_anh = $attributes['hinh_anh'] ?? null;
        $this->ngay_phat_hanh = $attributes['ngay_phat_hanh'] ?? now();
        $this->doanh_thu = $attributes['doanh_thu'] ?? 0;
    }

    private function generateCustomId()
    {
        return 'BH' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    // Gom các relationships vào một phương thức
    public function relationships()
    {
        return [
            'artist' => $this->belongsTo(Account::class, 'ma_tk_artist', 'ma_tk'),
            'album' => $this->belongsTo(AlbumModel::class, 'ma_album', 'ma_album'),
            'bai_hat_subartist' => $this->hasMany(SongSubArtistModel::class, 'ma_tk', 'ma_subartist'),
            'theloai_baihat' => $this->hasMany(GenreSongModel::class, 'ma_bai_hat', 'ma_bai_hat'),
            'thong_ke' => $this->hasOne(StatisticModel::class, 'ma_bai_hat', 'ma_bai_hat')
        ];
    }

    public function addSong($data)
    {
        $data['ma_bai_hat'] = $data['ma_bai_hat'] ?? $this->generateCustomId();
        return self::create($data);
    }

    public function updateSong($ma_bai_hat, $data)
    {
        $song = self::where('ma_bai_hat', $ma_bai_hat)->first();
        if ($song) {
            $song->update($data);
            return $song;
        }
        return null;
    }

    public function deleteSong($ma_bai_hat)
    {
        $song = self::where('ma_bai_hat', $ma_bai_hat)->first();
        if ($song) {
            return $song->delete();
        }
        return false;
    }
}
