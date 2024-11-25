<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $incrementing = false; 
    protected $keyType = 'string';
    protected $table = 'bank';
    protected $primaryKey = 'bank_id'; // Khóa chính
    protected $fillable = ['bank_id','bank_name', 'so_du_kha_dung'];

}
