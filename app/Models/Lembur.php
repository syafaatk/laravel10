<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\DetailLembur;
use App\Models\User;

class Lembur extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tanggal',
        'jenis',
        'jam_mulai',
        'jam_selesai',
        'durasi_jam',
        'keterangan',
        'status',
        'approver',
        'estimasi_uang_lembur',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_mulai' => 'datetime',
        'jam_selesai' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approved(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function detailLemburs(): HasMany
    {
        return $this->hasMany(DetailLembur::class);
    }
}