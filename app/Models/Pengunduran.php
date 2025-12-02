<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengunduran extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'requested_date',
        'reason',
        'status',
        'pic',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'requested_date' => 'date',
        'processed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

}