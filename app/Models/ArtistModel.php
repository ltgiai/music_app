<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtistModel extends Model
{
    use HasFactory;

    protected $table = 'artist'; // Bảng nghệ sĩ

    protected $fillable = ['ma_tk', 'ten_artist', 'anh_dai_dien', 'tong_tien'];

    public $timestamps = false;

    public function relationsships()
    {
        return [
            'account' => $this->account(),
        ];
    }

    // Quan hệ nghệ sĩ thuộc về 1 tài khoản
    public function account()
    {
        // 'ma_tk' của bảng 'artist' tham chiếu đến 'ma_tk' của bảng 'account'
        return $this->belongsTo(AccountModel::class, 'ma_tk', 'ma_tk');
    }

    public function song_subartist()
    {
        return $this->hasMany(SongSubArtistModel::class, 'ma_tk', 'ma_subartist');
    }

    public function artist_withdrawal_slip()
    {
        return $this->hasMany(ArtistWithdrawalSlipModel::class, 'ma_tk', 'ma_tk_artist');
    }

    // Phương thức thêm nghệ sĩ
    public static function createArtist($data)
    {
        // Kiểm tra dữ liệu hợp lệ trước khi thêm vào cơ sở dữ liệu
        return self::create([
            'ma_tk' => $data['ma_tk'],
            'artist_name' => $data['ten_artist'],
            'avatar' => $data['anh_dai_dien'],
            'total_revenue' => $data['tong_tien'],
        ]);
    }

    // Phương thức sửa nghệ sĩ
    public function updateArtist($data)
    {
        // Kiểm tra dữ liệu hợp lệ trước khi cập nhật
        return $this->update([
            'ma_tk' => $data['ma_tk'],
            'artist_name' => $data['ten_artist'],
            'avatar' => $data['anh_dai_dien'],
            'total_revenue' => $data['tong_tien'],
        ]);
    }

    // Phương thức xóa nghệ sĩ
    public function deleteArtist()
    {
        return $this->delete();
    }
}
