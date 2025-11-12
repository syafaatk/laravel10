<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Lembur;

class DetailLembur extends Model
{
    use HasFactory;

    protected $fillable = [
        'lembur_id',
        'uraian_pekerjaan',
    ];

    public function lembur(): BelongsTo
    {
        return $this->belongsTo(Lembur::class);
    }
}