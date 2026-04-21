<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengalamanKerja extends Model
{
    use HasFactory;

    protected $table = 'pengalaman_kerjas';

    protected $fillable = [
        'user_id',
        'perusahaan',
        'posisi',
        'tanggal_mulai',
        'tanggal_selesai',
        'deskripsi',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}