<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\KontrakDetail;


class Kontrak extends Model
{
    use HasFactory;
    protected $table = 'kontraks';

    protected $fillable = [
        'title_kontrak',
        'tanggal_mulai_kontrak',
        'tanggal_selesai_kontrak',
        'is_active',
    ];

    protected $casts = [
        'tanggal_mulai_kontrak' => 'date:Y-m-d',
        'tanggal_selesai_kontrak' => 'date:Y-m-d',
        'is_active' => 'boolean',
    ];

    public function kontrakDetails()
    {
        return $this->hasMany(KontrakDetail::class, 'kontrak_id');
    }
}