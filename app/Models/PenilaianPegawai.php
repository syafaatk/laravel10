<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenilaianPegawai extends Model
{
    use HasFactory;

    protected $table = 'penilaian_pegawais';

    protected $fillable = [
        'user_id',
        'reviewer_id',
        'review_date',
        'period_start',
        'period_end',
        'scores',
        'overall_score',
        'strengths',
        'weaknesses',
        'feedback',
    ];

    protected $casts = [
        'review_date' => 'date',
        'period_start' => 'date',
        'period_end' => 'date',
        'scores' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function getScoreForCriteria(string $criteria): ?int
    {
        return $this->scores[$criteria] ?? null;
    }
}
