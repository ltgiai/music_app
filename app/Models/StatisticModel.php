<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatisticModel extends Model
{
    use HasFactory;

    protected $table = 'thong_ke';

    // Không có khóa chính
    protected $primaryKey = null;
    public $incrementing = false;

    protected $keyType = 'string';
    protected $fillable = [
        'ngay_thong_ke', 
        'ma_bai_hat', 
        'doanh_thu', 
        'luot_nghe'
    ]; 
    public $timestamps = false;

    /**
     * Quan hệ với bảng bai_hat
     */
    public function song()
    {
        return $this->belongsTo(SongModel::class, 'ma_bai_hat', 'ma_bai_hat');
    }

    /**
     * Phương thức khởi tạo với các giá trị mặc định
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Gán giá trị mặc định cho các thuộc tính
        $this->attributes['ngay_thong_ke'] = $attributes['ngay_thong_ke'] ?? now()->format('Y-m-d'); // Ngày hiện tại
        $this->attributes['ma_bai_hat'] = $attributes['ma_bai_hat'] ?? null;
        $this->attributes['doanh_thu'] = $attributes['doanh_thu'] ?? 0; // Mặc định doanh thu là 0
        $this->attributes['luot_nghe'] = $attributes['luot_nghe'] ?? 0; // Mặc định lượt nghe là 0
    }
}
