<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlipGaji extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'gaji_id',
        'user_id',
        'gaji_pokok',
        'tunjangan_jabatan',
        'tunjangan_golongan',
        'tunjangan_makan',
        'tunjangan_rumah',
        'tunjangan_transport',
        'tunjangan_tambahan',
        'tunjangan_extra',
        'premi_jkk_jkm',
        'potongan_pph21',
        'potongan_jmo',
        'potongan_lain',
        'total_tunjangan',
        'penghasilan_bruto',
        'total_potongan',
        'penghasilan_netto',
        'catatan',
    ];

    public function gaji()
    {
        return $this->belongsTo(Gaji::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}