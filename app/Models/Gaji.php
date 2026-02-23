<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gaji extends Model
{
    use HasFactory;

    protected $fillable = [
        'periode_bulan',
        'rentang_mulai',
        'rentang_selesai',
        'status',
    ];

    protected $casts = [
        'rentang_mulai' => 'date',
        'rentang_selesai' => 'date',
    ];

    public function slipGaji()
    {
        return $this->hasMany(SlipGaji::class);
    }
}