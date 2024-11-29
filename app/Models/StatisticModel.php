<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatisticModel extends Model
{
    use HasFactory;

    protected $table = 'thong_ke';

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['ngay_thong_ke', 'ma_bai_hat', 'doanh_thu', 'luot_nghe'];
    public $timestamps = false;

    private $ngay_thong_ke;
    private $ma_bai_hat;
    private $doanh_thu;
    private $luot_nghe;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Gán giá trị hoặc giá trị mặc định
        $this->ngay_thong_ke = $attributes['ngay_thong_ke'] ?? $this->generateCustomId();
        $this->ma_bai_hat = $attributes['ma_bai_hat'] ?? null;
        $this->doanh_thu = $attributes['doanh_thu'] ?? 0; // Mặc định doanh thu là 0
        $this->luot_nghe = $attributes['luot_nghe'] ?? 0; // Mặc định lượt nghe là 0
    }

    private function generateCustomId()
    {
        return now()->format('Ymd'); // Ví dụ: Tạo ID theo ngày tháng năm (20241129)
    }

    // Gom các relationships vào một phương thức
    public function relationships()
    {
        return [
            'song' => $this->belongsTo(SongModel::class, 'ma_bai_hat', 'ma_bai_hat'),
        ];
    }
}
