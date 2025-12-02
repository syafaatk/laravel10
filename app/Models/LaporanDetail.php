<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Laporan;

class LaporanDetail extends Model
{
    use HasFactory;
    protected $table = 'laporan_details';

    protected $fillable = [
        'laporan_id',
        'judul_modul',
        'judul_pekerjaan',
        'progress_pekerjaan',
        'attachment_screenshots',
        'is_lock',
    ];

    protected $casts = [
        'is_lock' => 'boolean',
    ];

    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'laporan_id');
    }
}