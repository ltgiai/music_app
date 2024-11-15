<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenreModel extends Model
{
    use HasFactory;

    protected $table = 'the_loai';
    public $timestamps = false;

    protected $fillable = [
        'ma_the_loai',
        'ten_the_loai',
    ];

}
