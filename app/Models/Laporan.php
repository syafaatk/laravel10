<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\KontrakLaporan;
use App\Models\User;

class Laporan extends Model
{
    use HasFactory;
    protected $table = 'laporans';

    protected $fillable = [
        'kontrak_laporan_id',
        'user_id',
        'attachment_file_daily',
        'is_lock',
    ];

    protected $casts = [
        'is_lock' => 'boolean',
    ];

    public function kontrakLaporan()
    {
        return $this->belongsTo(KontrakLaporan::class, 'kontrak_laporan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function laporanDetails()
    {
        return $this->hasMany(LaporanDetail::class, 'laporan_id');
    }
}