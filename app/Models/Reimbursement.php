<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reimbursement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'tipe',
        'description',
        'amount',
        'status',
        'attachment',
        'attachment_note',
        'laporan_reimbursement_id',
        'processed_by',
        'processed_at',
        'lunch_event_id',
    ];

    protected $casts = [
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

    public function laporanReimbursement(): BelongsTo
    {
        return $this->belongsTo(LaporanReimbursement::class, 'laporan_reimbursement_id');
    }

    public function lunchEvent(): BelongsTo
    {
        return $this->belongsTo(LunchEvent::class);
    }
}