<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;


class SongModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bai_hat';

    protected $primaryKey = 'ma_bai_hat';
    public $incrementing = false;
    protected $keyType = 'string';

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

    protected $dates = ['deleted_at'];

    // Hàm tạo mã bài hát
    private function generateCustomId()
    {
        $lastId = self::max('ma_bai_hat');
        $nextId = (int)substr($lastId, 2) + 1;
        return 'BH' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
    }

    // Quan hệ với tài khoản (nghệ sĩ)
    public function artist()
    {
        return $this->belongsTo(Account::class, 'ma_tk_artist', 'ma_tk');
    }

    // Quan hệ với album
    public function album()
    {
        return $this->belongsTo(AlbumModel::class, 'ma_album', 'ma_album');
    }

    // Quan hệ với bảng `bai_hat_subartist`
    public function baiHatSubArtist()
    {
        return $this->hasMany(SongSubArtistModel::class, 'ma_bai_hat', 'ma_bai_hat');
    }

    // Quan hệ với bảng `theloai_baihat`
    public function theloaiBaiHat()
    {
        return $this->hasMany(GenreSongModel::class, 'ma_bai_hat', 'ma_bai_hat');
    }

    // Thêm bài hát mới
    public function addSong($data)
    {
        try {
            $data['ma_bai_hat'] = $data['ma_bai_hat'] ?? $this->generateCustomId();
            return self::create($data);
        } catch (\Exception $e) {
            Log::error('Error adding song: ' . $e->getMessage());
            return false;
        }
    }

    // Cập nhật bài hát
    public function updateSong($ma_bai_hat, $data)
    {
        try {
            $song = self::find($ma_bai_hat);
            if ($song) {
                $song->update($data);
                return $song;
            }
            return null;
        } catch (\Exception $e) {
            Log::error('Error updating song: ' . $e->getMessage());
            return false;
        }
    }

    // Xóa bài hát (soft delete)
    public function deleteSong($ma_bai_hat)
    {
        try {
            $song = self::find($ma_bai_hat);
            if ($song) {
                $song->delete();
                return true;
            }
            return false;
        } catch (\Exception $e) {
            Log::error('Error deleting song: ' . $e->getMessage());
            return false;
        }
    }
}
