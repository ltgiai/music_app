<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtistWithdrawalSlipModel extends Model
{
    use HasFactory;

    protected $table = 'phieu_rut_tien_artist';

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['ma_phieu', 'ma_tk_artist', 'ngay_rut_tien', 'tong_tien_rut_ra', 'bank_id', 'bank_name'];
    public $timestamps = false;

    private $ma_phieu;
    private $ma_tk_artist;
    private $ngay_rut_tien;
    private $tong_tien_rut_ra;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->ma_phieu = $attributes['ma_phieu'] ?? $this->generateCustomId();
        $this->ma_tk_artist = $attributes['ma_tk_artist'] ?? null;
        $this->ngay_rut_tien = $attributes['ngay_rut_tien'] ?? null;
        $this->tong_tien_rut_ra = $attributes['tong_tien_rut_ra'] ?? 0;
    }

    private function generateCustomId()
    {
        return 'ART' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    // Gom các relationships vào một phương thức
    public function relationships()
    {
        return [
            'artist' => $this->belongsTo(Account::class, 'ma_subartist', 'ma_tk'), // Artist - Account : 1 - 1
        ];
    }

    public function addWithdrawalSlip($data)
    {
        $data['id'] = $data['id'] ?? $this->generateCustomId();
        return self::create($data);
    }

    public function updateWithdrawalSlip($id, $data)
    {
        $slip = self::find($id);
        if ($slip) {
            $slip->update($data);
            return $slip;
        }
        return null;
    }

    public function deleteWithdrawalSlip($id)
    {
        $slip = self::find($id);
        if ($slip) {
            return $slip->delete();
        }
        return false;
    }

    /**
     * Get the value of ma_phieu
     */ 
    public function getMa_phieu()
    {
        return $this->ma_phieu;
    }

    /**
     * Set the value of ma_phieu
     *
     * @return  self
     */ 
    public function setMa_phieu($ma_phieu)
    {
        $this->ma_phieu = $ma_phieu;

        return $this;
    }

    /**
     * Get the value of ma_tk_artist
     */ 
    public function getMa_tk_artist()
    {
        return $this->ma_tk_artist;
    }

    /**
     * Set the value of ma_tk_artist
     *
     * @return  self
     */ 
    public function setMa_tk_artist($ma_tk_artist)
    {
        $this->ma_tk_artist = $ma_tk_artist;

        return $this;
    }

    /**
     * Get the value of ngay_rut_tien
     */ 
    public function getNgay_rut_tien()
    {
        return $this->ngay_rut_tien;
    }

    /**
     * Set the value of ngay_rut_tien
     *
     * @return  self
     */ 
    public function setNgay_rut_tien($ngay_rut_tien)
    {
        $this->ngay_rut_tien = $ngay_rut_tien;

        return $this;
    }

    /**
     * Get the value of tong_tien_rut_ra
     */ 
    public function getTong_tien_rut_ra()
    {
        return $this->tong_tien_rut_ra;
    }

    /**
     * Set the value of tong_tien_rut_ra
     *
     * @return  self
     */ 
    public function setTong_tien_rut_ra($tong_tien_rut_ra)
    {
        $this->tong_tien_rut_ra = $tong_tien_rut_ra;

        return $this;
    }
}
