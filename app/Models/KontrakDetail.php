<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kontrak;


class KontrakDetail extends Model
{
    use HasFactory;
    protected $table = 'kontrak_details';

    protected $fillable = [
        'kontrak_id',
        'title_spph',
        'tgl_mulai_spph',
        'tgl_selesai_spph',
        'is_active',
    ];

    protected $casts = [
        'tgl_mulai_spph' => 'date:Y-m-d',
        'tgl_selesai_spph' => 'date:Y-m-d',
        'is_active' => 'boolean',
    ];

    public function kontrak()
    {
        return $this->belongsTo(Kontrak::class, 'kontrak_id');
    }

    public function kontrakLaporans()
    {
        return $this->hasMany(KontrakLaporan::class, 'kontrak_detail_id');
    }

    public function userKontrakDetails()
    {
        return $this->hasMany(DetailKontrakUser::class, 'kontrak_detail_id');
    }
}