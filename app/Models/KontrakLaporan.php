<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\KontrakDetail;


class KontrakLaporan extends Model
{
    use HasFactory;
    protected $table = 'kontrak_laporans';

    protected $fillable = [
        'kontrak_detail_id',
        'bulan_tahun',
        'tgl_periode_mulai',
        'tgl_periode_selesai',
        'is_lock',
    ];

    protected $casts = [
        'tgl_periode_mulai' => 'date:Y-m-d',
        'tgl_periode_selesai' => 'date:Y-m-d',
        'is_lock' => 'boolean',
    ];

    public function kontrakDetail()
    {
        return $this->belongsTo(KontrakDetail::class, 'kontrak_detail_id');
    }

    public function laporans()
    {
        return $this->hasMany(Laporan::class, 'kontrak_laporan_id');
    }
}